<?php


namespace App\Helpers;


use App\Post;

class GeneratorHelper
{
    protected static function forStringGenerator($length, $characterArray, $charactersLength)
    {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characterArray[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    protected static function randomString($length = 6, $type = "ALL")
    {
        $allCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numericUpperCaseCharacters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numericLowerCaseCharacters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $upperCaseCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerCaseCharacters = 'abcdefghijklmnopqrstuvwxyz';
        $numericCharacters = '0123456789';
        switch ($type)
        {
            case 'ALL':
                $charactersLength = strlen($allCharacters);
                return GeneratorHelper::forStringGenerator($length, $allCharacters, $charactersLength);
            case 'NUM_UPPER':
                $charactersLength = strlen($numericUpperCaseCharacters);
                return GeneratorHelper::forStringGenerator($length, $numericUpperCaseCharacters, $charactersLength);
            case 'NUM_LOWER':
                $charactersLength = strlen($numericLowerCaseCharacters);
                return GeneratorHelper::forStringGenerator($length, $numericLowerCaseCharacters, $charactersLength);
            case 'UPPER':
                $charactersLength = strlen($upperCaseCharacters);
                return GeneratorHelper::forStringGenerator($length, $upperCaseCharacters, $charactersLength);
            case 'LOWER':
                $charactersLength = strlen($lowerCaseCharacters);
                return GeneratorHelper::forStringGenerator($length, $lowerCaseCharacters, $charactersLength);
            case 'NUM':
                $charactersLength = strlen($numericCharacters);
                return GeneratorHelper::forStringGenerator($length, $numericCharacters, $charactersLength);
        }
    }

    public static function code(string $belongs, int $length = 6)
    {
        switch (strtoupper($belongs))
        {
            case 'POSTS':
                $code = 'P'.GeneratorHelper::randomString($length, 'NUM_UPPER');
                while (Post::where('code', $code)->exists())
                {
                    $code = 'P'.GeneratorHelper::randomString($length, 'NUM_UPPER');
                }
                return $code;
        }
    }
}
