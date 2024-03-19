<?php

namespace Iam444\TestTask;

class Reverter {

    /**
     * Reverter doesn't support foreign punctuation except RU and EN marks.
     * Extend array with other symbols if it's needed.
     */
    private const PUNCTUATION_MARKS =  [ '\'', '"', ',', '.', '...', '`', ';', ':', '?', '!', '–', '—', '-', '“', '”', '[', ']', '(', ')', '«', '»' ];

    /**
     * @var string[]
     */
    private static array $sourceCharsList = [];

    /**
     * @var array[]
     */
    private static array $resultCharsListData = [];

    /**
     * @param string $string
     * @return string
     */
    public static function revert(string $string): string {

        self::$sourceCharsList = mb_str_split($string);

        if ( $string === '' || count( self::$sourceCharsList ) === 1 ) {
            return $string;
        }

        $wordToRevertFirstCharIndex = -1;

        foreach ( self::$sourceCharsList as $index => $char ) {

            if ( in_array( $char, self::PUNCTUATION_MARKS ) || $char === ' ' ) {
                self::$resultCharsListData[] = [ 'isPunctuationMark' => true, 'value' => $char ];

                if ( $wordToRevertFirstCharIndex !== -1 ) {
                    /**
                     * $mutableSubstringFirstCharIndex < $index - 1
                     * Using strict comparison here to avoid useless revert operations on single character.
                     * Always get the same in result: "'Р." -> "'Р." OR "!A " -> "!A " etc.
                     */
                    if ( $wordToRevertFirstCharIndex < $index - 1 ) {
                        self::revertSingleWord( $wordToRevertFirstCharIndex, $index - 1) ;
                    }

                    $wordToRevertFirstCharIndex = -1;
                }
            } else {
                if ( $wordToRevertFirstCharIndex === -1 ) {
                    $wordToRevertFirstCharIndex = $index;
                }

                self::$resultCharsListData[] = [
                    'isPunctuationMark' => false,
                    'isUpperCase'       => mb_strtoupper($char) === $char,
                    'value'             => mb_strtolower($char),
                ];

                if ( !key_exists( $index + 1, self::$sourceCharsList ) ) {
                    self::revertSingleWord( $wordToRevertFirstCharIndex, $index );
                }
            }
        }

        return self::buildResultString();
    }

    /**
     * @param int $wordToRevertFirstCharIndex
     * @param int $wordToRevertLastCharIndex
     * @return void
     */
    private static function revertSingleWord(int $wordToRevertFirstCharIndex, int $wordToRevertLastCharIndex): void {
        for ( $i = $wordToRevertFirstCharIndex; $i <= $wordToRevertLastCharIndex; $i++ ) {
            $sourceCharKey = $wordToRevertFirstCharIndex + ($wordToRevertLastCharIndex - $i);
            self::$resultCharsListData[$i]['value'] = mb_strtolower( self::$sourceCharsList[$sourceCharKey] );
        }
    }

    /**
     * @return string
     */
    private static function buildResultString(): string {
        $result = '';

        foreach ( self::$resultCharsListData as $charDataRow ) {
            if ( !$charDataRow['isPunctuationMark'] && $charDataRow['isUpperCase'] ) {
                $result .=  mb_strtoupper( $charDataRow['value'] );
            } else {
                $result .= $charDataRow['value'];
            }
        }

        self::clear();

        return $result;
    }

    private static function clear(): void {
        self::$resultCharsListData = [];
        self::$sourceCharsList = [];
    }
}
