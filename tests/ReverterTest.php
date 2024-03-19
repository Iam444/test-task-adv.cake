<?php declare(strict_types=1);

namespace Iam444\TestTask;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ReverterTest extends TestCase
{
    public static function string_with_different_words_quantity_provider(): array
    {
        return [
            'One word' => ['Cat', 'Tac'],
            'Two words' => ['Rise against', 'Esir tsniaga'],
            'Three words' => ['Is cold now', 'Si dloc won'],
            'Ten words' => [
                'Каждый охотник желает знать где сидит черный, белый, серый фазан',
                'Йыджак кинтохо теалеж ьтанз едг тидис йынреч, йылеб, йырес назаф'
            ],
            'Many words' => [
                'Банальные, но неопровержимые выводы, а также стремящиеся вытеснить традиционное производство, нанотехнологии освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, заблокированы в рамках своих собственных рациональных ограничений. Ясность нашей позиции очевидна: глубокий уровень погружения способствует подготовке и реализации поставленных обществом задач. И нет сомнений, что реплицированные с зарубежных источников, современные исследования обнародованы.',
                'Еыньланаб, он еымижреворпоен ыдовыв, а ежкат ясеищямертс ьтинсетыв еонноицидарт овтсдовзиорп, ииголонхетонан тюащевсо онйачывзерч еынсеретни итсоннебосо ынитрак в молец, окандо еынтеркнок ыдовыв, ястеемузар, ынавориколбаз в хакмар хиовс хынневтсбос хыньланоицар йинечинарго. Ьтсонся йешан иицизоп андивечо: йикобулг ьневору яинежургоп теувтсбосопс еквотогдоп и иицазилаер хыннелватсоп мовтсещбо чадаз. И тен йиненмос, отч еыннаворицилпер с хынжебураз вокинчотси, еыннемервос яинаводелсси ынаводоранбо.',
            ],
        ];
    }

    public static function few_char_string_provider(): array
    {
        return [
            'Punctuation mark'             => [':', ':'],
            'Alphabetical symbol'          => ['Y', 'Y'],
            'Non-alphabetical symbol'      => ['&', '&'],
            'Space character'              => [' ', ' '],
            'Empty string'                 => ['', ''],
        ];
    }

    public static function string_with_different_alphabetical_symbols_provider(): array
    {
        return [
            'Eastern' => [
                'ン コ! ゎツユ ぺ とゕ;やソ ゴふギポプ タびニム〆ボ',
                'ン コ! ユツゎ ぺ ゕと;ソや プポギふゴ ボ〆ムニびタ',
            ],
            'Latin' => [
                'The quick brown fox jumps over the [lazy] dog',
                'Eht kciuq nworb xof spmuj revo eht [yzal] god',
            ],
            'Cyrillic' => [
                'Съешь же ещ этих мягких французских булок, да выпей чаю',
                'Ьшеъс еж ще хитэ хикгям хиксзуцнарф колуб, ад йепыв юач',
            ],
            'Mixed aplphabets' => [
                'Съgеム 〆шdhь же ещ эふиhх мягdム〆ких фjраム〆нцgу ふзсfhkкふих булоkк, дhfа выпеfй чаム〆sdю',
                'ムеgъС Ьhdш〆 еж ще хHиふэ хик〆ムDгям уgцн〆ムарjф ХиふкkhfСзふ кkолуб, аfhд йfепыв юdS〆ムач',
            ],
        ];
    }

    public static function string_with_different_symbols_at_the_end_provider(): array
    {
        return [
            'With punctuation mark' => ['It\'s cool!', 'Ti\'s looc!'],
            'With letter' => ['Also cool', 'Osla looc'],
            'With arithmetical operation mark' => ['Cool *', 'Looc *'], // Arithmetical operation marks are NOT a punctuation marks (except "-")
            'With "-"' => ['Cool +-', 'Looc +-'], // Arithmetical operation marks are NOT a punctuation marks (except "-")
        ];
    }

    public static function string_with_different_symbol_cases_provider(): array
    {
        return [
            'With only lower case' => ['simple string', 'elpmis gnirts'],
            'With only upper case' => ['HERE WE GO!', 'EREH EW OG!'],
            'With mixed case'      => ['\'elEpHant\'', '\'tnAhPele\''],
        ];
    }

    public static function string_with_compound_words_provider(): array
    {
        return [
            'With apostrophe' => ['can`t', 'nac`t'],
            'With hyphen' => ['third-part', 'driht-trap'],
        ];
    }

    public static function string_with_all_chars_same_type_provider(): array
    {
        return [
            'With all chars are punctuation marks' => ['\'",....`;:?!–—-“”[]()«»', '\'",....`;:?!–—-“”[]()«»'],
            'With all chars are alphabetical symbols' => ['asdfghjklqwertyuiopzxcvbnm', 'mnbvcxzpoiuytrewqlkjhgfdsa'],
            'With all chars are non-alphabetical symbols' => ['&^%#№=*/@', '@/*=№#%^&'],
        ];
    }

    #[DataProvider('string_with_different_alphabetical_symbols_provider')]
    #[DataProvider('string_with_different_symbols_at_the_end_provider')]
    #[DataProvider('string_with_different_symbol_cases_provider')]
    #[DataProvider('string_with_compound_words_provider')]
    #[DataProvider('string_with_all_chars_same_type_provider')]
    #[DataProvider('few_char_string_provider')]
    #[DataProvider('string_with_different_words_quantity_provider')]
    public function test_revert_strings_with_different_content($provided, $expected): void {
        $result = Reverter::revert($provided);

        $this->assertSame($expected, $result);
    }

    public static function typical_strings_provider(): array
    {
        return [
            ['Kitty-cat', 'Yttik-tac'],
            ['houSe=домИК', 'кимОд=esuOH'],
        ];
    }

    #[DataProvider('typical_strings_provider')]
    public function test_static_props_reinitialisation_after_revert($provided, $expected): void {
        $result = Reverter::revert($provided);

        $this->assertSame($expected, $result);

        $staticProp1 = new \ReflectionProperty( Reverter::class, 'sourceCharsList');
        $staticProp2 = new \ReflectionProperty( Reverter::class, 'resultCharsListData');
        $this->assertSame( [], $staticProp1->getValue() );
        $this->assertSame( [], $staticProp2->getValue() );
    }
}
