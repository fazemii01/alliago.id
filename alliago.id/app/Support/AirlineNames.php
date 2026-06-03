<?php

namespace App\Support;

class AirlineNames
{
    private static array $names = [
        // Indonesia domestic
        'GA' => 'Garuda Indonesia',
        'QG' => 'Citilink',
        'JT' => 'Lion Air',
        'ID' => 'Batik Air',
        'IW' => 'Wings Air',
        'SJ' => 'Sriwijaya Air',
        'IN' => 'Nam Air',
        'XN' => 'Xpressair',
        'XT' => 'Indonesia AirAsia X',
        'QZ' => 'AirAsia Indonesia',
        'IL' => 'Trigana Air',
        'FS' => 'Susi Air',
        'IP' => 'Pelita Air',
        // Regional / common international
        'SQ' => 'Singapore Airlines',
        'MH' => 'Malaysia Airlines',
        'AK' => 'AirAsia',
        'D7' => 'AirAsia X',
        'FD' => 'Thai AirAsia',
        'TG' => 'Thai Airways',
        'PR' => 'Philippine Airlines',
        'CX' => 'Cathay Pacific',
        'HX' => 'Hong Kong Airlines',
        'UO' => 'Hong Kong Express',
        'VN' => 'Vietnam Airlines',
        'VJ' => 'VietJet Air',
        'QH' => 'Bamboo Airways',
        'OD' => 'Malindo Air',
        'MI' => 'SilkAir',
        'TR' => 'Scoot',
        'EK' => 'Emirates',
        'EY' => 'Etihad Airways',
        'QR' => 'Qatar Airways',
        'GF' => 'Gulf Air',
        'WY' => 'Oman Air',
        'AI' => 'Air India',
        'UK' => 'Vistara',
        'NH' => 'ANA',
        'JL' => 'Japan Airlines',
        'KE' => 'Korean Air',
        'OZ' => 'Asiana Airlines',
        'CA' => 'Air China',
        'CZ' => 'China Southern',
        'MU' => 'China Eastern',
        'HU' => 'Hainan Airlines',
        'AF' => 'Air France',
        'KL' => 'KLM',
        'LH' => 'Lufthansa',
        'BA' => 'British Airways',
        'TK' => 'Turkish Airlines',
        'LX' => 'Swiss',
        'UA' => 'United Airlines',
        'AA' => 'American Airlines',
        'DL' => 'Delta Air Lines',
        'QF' => 'Qantas',
        'NZ' => 'Air New Zealand',
    ];

    public static function get(string $iata): string
    {
        return static::$names[strtoupper($iata)] ?? $iata;
    }
}
