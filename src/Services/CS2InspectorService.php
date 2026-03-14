<?php

namespace SteamApi\Services;

use Exception;

class CS2InspectorService
{
    private const MIN_DECODE_SCORE = 5;

    // Field maps for the main item
    private const ITEM_VARINT_FIELDS = [
        1 => 'account_id', 2 => 'asset_id', 3 => 'def_index', 4 => 'paint_index',
        5 => 'rarity', 6 => 'quality', 8 => 'paint_seed', 9 => 'killeater_score_type',
        10 => 'killeater_value', 13 => 'inventory', 14 => 'origin', 15 => 'quest_id',
        16 => 'drop_reason', 17 => 'music_index', 19 => 'pet_index', 21 => 'style',
        23 => 'upgrade_level'
    ];

    private const ITEM_STICKER_LIST_FIELDS = [
        12 => 'stickers', 20 => 'keychains', 22 => 'variations'
    ];

    // Field maps for stickers/keychains (these were already correct)
    private const STICKER_VARINT_FIELDS = [
        1 => 'slot', 2 => 'sticker_id', 6 => 'tint_id', 10 => 'pattern',
        11 => 'highlight_reel', 12 => 'wrapped_sticker'
    ];

    private const STICKER_FIXED32_FIELDS = [
        3 => 'wear', 4 => 'scale', 5 => 'rotation', 7 => 'offset_x',
        8 => 'offset_y', 9 => 'offset_z'
    ];

    // Fields used to score a successful decode (UPDATED KEYS)
    private const SCORED_FIELDS = [
        'asset_id', 'account_id', 'paint_index', 'paint_seed', 'quality',
        'rarity', 'inventory', 'origin', 'paint_wear'
    ];

    /**
     * Main method. Accepts either a full steam:// link or just a hex string.
     */
    public static function inspect(string $input): array {
        $hex = self::extractHexPayload($input);
        if (!$hex) {
            throw new Exception("Invalid link or string format (masked payload not found).");
        }

        $raw = hex2bin($hex);
        if ($raw === false || strlen($raw) === 0) {
            throw new Exception("Masked payload is empty or contains invalid HEX.");
        }

        // Only one decryption method is used: XOR with the first byte
        $transformed = self::xorMask($raw, ord($raw[0]));
        $payload = self::unwrapMaskedPayload($transformed);

        try {
            $parsed = self::parseEconItem($payload);
            $score = self::scoreDecodedItem($parsed);

            if ($score >= self::MIN_DECODE_SCORE) {
                return $parsed;
            }
        } catch (Exception $e) {
            // If parsing fails, propagate the error below
        }

        throw new Exception("Failed to decrypt inspect link data.");
    }

    private static function extractHexPayload(string $input): ?string {
        if (preg_match('/csgo_econ_action_preview(?:%20| )([0-9A-Fa-f]+)$/i', $input, $matches)) {
            return strtoupper($matches[1]);
        }
        $cleaned = trim($input);
        if (preg_match('/^[0-9A-Fa-f]+$/i', $cleaned)) {
            return strtoupper($cleaned);
        }
        return null;
    }

    private static function xorMask(string $data, int $key): string {
        return $data ^ str_repeat(chr($key), strlen($data));
    }

    private static function unwrapMaskedPayload(string $data): string {
        if (strlen($data) >= 5 && ord($data[0]) === 0x00) {
            return substr($data, 1, -4);
        }
        return $data;
    }

    private static function scoreDecodedItem(array $item): int {
        $score = 0;
        // Replaced defindex with def_index
        if (isset($item['def_index'])) $score += 4;

        foreach (self::SCORED_FIELDS as $field) {
            if (isset($item[$field])) $score += 1;
        }

        $score += count($item['stickers'] ?? []);
        $score += count($item['keychains'] ?? []);
        $score += count($item['variations'] ?? []);
        return $score;
    }

    // ==========================================
    // LOCAL PROTOBUF PARSER
    // ==========================================

    private static function parseEconItem(string $buffer): array {
        $item = [
            'stickers' => [],
            'keychains' => [],
            'variations' => [],
        ];
        $offset = 0;
        $len = strlen($buffer);

        while ($offset < $len) {
            $tag = self::readVarint($buffer, $offset);
            $fieldNum = $tag >> 3;
            $wireType = $tag & 0x7;

            // 1. Simple integer fields (matches keys from ITEM_VARINT_FIELDS)
            if (isset(self::ITEM_VARINT_FIELDS[$fieldNum])) {
                $fieldName = self::ITEM_VARINT_FIELDS[$fieldNum];
                $item[$fieldName] = self::readVarint($buffer, $offset);
                continue;
            }

            // 2. Skin wear (float packed as varint) - changed to paint_wear
            if ($fieldNum === 7) {
                $val = self::readVarint($buffer, $offset);
                $item['paint_wear'] = self::bytesToFloatBits($val);
                continue;
            }

            // 3. Custom name - changed to custom_name
            if ($fieldNum === 11) {
                $raw = self::readLengthDelimited($buffer, $offset);
                $item['custom_name'] = $raw;
                continue;
            }

            // 4. Lists (stickers, keychains, variations)
            if (isset(self::ITEM_STICKER_LIST_FIELDS[$fieldNum])) {
                $listName = self::ITEM_STICKER_LIST_FIELDS[$fieldNum];
                $raw = self::readLengthDelimited($buffer, $offset);
                $item[$listName][] = self::parseSticker($raw);
                continue;
            }

            // 5. Entindex (with zigzag decoding) - changed to ent_index
            if ($fieldNum === 18) {
                $val = self::readVarint($buffer, $offset);
                $item['ent_index'] = self::zigzagDecode($val);
                continue;
            }

            // Skip unknown fields
            self::skipField($buffer, $offset, $wireType);
        }
        return $item;
    }

    private static function parseSticker(string $buffer): array {
        $sticker = [];
        $offset = 0;
        $len = strlen($buffer);

        while ($offset < $len) {
            $tag = self::readVarint($buffer, $offset);
            $fieldNum = $tag >> 3;
            $wireType = $tag & 0x7;

            // Integer sticker parameters
            if (isset(self::STICKER_VARINT_FIELDS[$fieldNum])) {
                $fieldName = self::STICKER_VARINT_FIELDS[$fieldNum];
                $sticker[$fieldName] = self::readVarint($buffer, $offset);
                continue;
            }

            // Float sticker parameters (wear, rotation, offset)
            if (isset(self::STICKER_FIXED32_FIELDS[$fieldNum])) {
                $fieldName = self::STICKER_FIXED32_FIELDS[$fieldNum];
                $val = self::readFixed32($buffer, $offset);
                $sticker[$fieldName] = self::fixed32ToFloat($val);
                continue;
            }

            self::skipField($buffer, $offset, $wireType);
        }
        return $sticker;
    }

    // --- Binary data reading utilities ---

    private static function readVarint(string $buffer, int &$offset): int {
        $result = 0;
        $shift = 0;
        $len = strlen($buffer);

        while (true) {
            if ($offset >= $len) throw new Exception("Unexpected end of buffer while reading varint");
            $byte = ord($buffer[$offset++]);
            $result |= ($byte & 0x7F) << $shift;

            if (!($byte & 0x80)) return $result;

            $shift += 7;
            if ($shift > 70) throw new Exception("Varint is too long");
        }
    }

    private static function readFixed32(string $buffer, int &$offset): int {
        if ($offset + 4 > strlen($buffer)) throw new Exception("Unexpected end of buffer while reading fixed32");
        $bytes = substr($buffer, $offset, 4);
        $offset += 4;
        $unpacked = unpack('V', $bytes); // V = 32-bit little-endian
        return $unpacked[1];
    }

    private static function readLengthDelimited(string $buffer, int &$offset): string {
        $length = self::readVarint($buffer, $offset);
        if ($offset + $length > strlen($buffer)) throw new Exception("Field length exceeds buffer size");
        $data = substr($buffer, $offset, $length);
        $offset += $length;
        return $data;
    }

    private static function skipField(string $buffer, int &$offset, int $wireType): void {
        if ($wireType === 0) {
            self::readVarint($buffer, $offset);
        } elseif ($wireType === 1) {
            $offset += 8;
        } elseif ($wireType === 2) {
            $length = self::readVarint($buffer, $offset);
            $offset += $length;
        } elseif ($wireType === 5) {
            $offset += 4;
        } else {
            throw new Exception("Unsupported wire type: $wireType");
        }
    }

    // --- Converters and math ---

    private static function bytesToFloatBits(int $value): float {
        $packed = pack('N', $value & 0xFFFFFFFF); // Big-Endian
        $unpacked = unpack('G', $packed);
        return $unpacked[1];
    }

    private static function fixed32ToFloat(int $value): float {
        $packed = pack('V', $value & 0xFFFFFFFF); // Little-Endian
        $unpacked = unpack('g', $packed);
        return $unpacked[1];
    }

    private static function zigzagDecode(int $value): int {
        return ($value >> 1) ^ -($value & 1);
    }
}