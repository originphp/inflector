<?php
/**
 * OriginPHP Framework
 * Copyright 2018 - 2019 Jamiel Sharief.
 *
 * Licensed under The MIT License
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * @copyright   Copyright (c) Jamiel Sharief
 * @link        https://www.originphp.com
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types = 1);
namespace Origin\Inflector;

use InvalidArgumentException;

/**
 * Inflector - The inflector defines standard rules which is suitable for most projects, but is not considered nor intented to be
 * complete. You can add custom rules for any words that might not be picked up.
 */
class Inflector
{
    /**
     * Holds caching from functions.
     *
     * @var array
     */
    private static $cache = [];

    /**
     * Inflector plural rules
     *
     * These rules have been ported from the Ruby On Rails Framework.
     *
     * @var array
     */
    private static $plural = [
        '/(quiz)$/i' => '\1zes',
        '/^(oxen)$/i' => '\1',
        '/^(ox)$/i' => '\1en',
        '/^(m|l)ice$/i' => '\1ice',
        '/^(m|l)ouse$/i' => '\1ice',
        '/(matr|vert|ind)(?:ix|ex)$/i' => '\1ices',
        '/(x|ch|ss|sh)$/i' => '\1es',
        '/([^aeiouy]|qu)y$/i' => '\1ies',
        '/(hive)$/i' => '\\1s',
        '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
        '/sis$/i' => 'ses',
        '/([ti])a$/i' => '\1a',
        '/([ti])um$/i' => '\1a',
        '/(buffal|tomat)o$/i' => '\1oes',
        '/(bu)s$/i' => '\1ses',
        '/(alias|status)$/i' => '\1es',
        '/(octop|vir)i$/i' => '\1i',
        '/(octop|vir)us$/i' => '\1i',
        '/^(ax|test)is$/i' => '\1es',
        '/s$/' => 's',
        '/$/' => 's', # important
    ];

    /**
     * Inflector singular rules
     *
     * These rules have been ported from the Ruby On Rails Framework.
     *
     * @var array
     */
    private static $singular = [
        '/(database)s$/i' => '\1',
        '/(quiz)zes$/i' => '\1',
        '/(matr)ices$/i' => '\1ix',
        '/(vert|ind)ices$/i' => '\1ex',
        '/^(ox)en/i' => '\1',
        '/(alias|status)(es)?$/i' => '\1',
        '/(octop|vir)(us|i)$/i' => '\1us',
        '/^(a)x[ie]s$/i' => '\1xis',
        '/(cris|test)(is|es)$/i' => '\1is',
        '/(shoe)s$/i' => '\1',
        '/(o)es$/i' => '\1',
        '/(bus)(es)?$/i' => '\1',
        '/^(m|l)ice$/i' => '\1ouse',
        '/(x|ch|ss|sh)es$/i' => '\1',
        '/(m)ovies$/i' => '\1ovie',
        '/(s)eries$/i' => '\1eries',
        '/([^aeiouy]|qu)ies$/i' => '\1y',
        '/([lr])ves$/i' => '\1f',
        '/(tive)s$/i' => '\1',
        '/(hive)s$/i' => '\1',
        '/([^f])ves$/i' => '\1fe',
        '/(^analy)(sis|ses)$/i' => '\1sis',
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)(sis|ses)$/i' => '\1sis',
        '/([ti])a$/i' => '\1um',
        '/(n)ews$/i' => '\1ews',
        '/(ss)$/i' => '\1',
        '/s$/i' => '',
    ];

    /**
     * Inflection irregular dictonary
     *
     * @var array
     */
    private static $irregular = [
        'child' => 'children',
        'criterion' => 'criteria',
        'man' => 'men',
        'money' => 'monies',
        'niche' => 'niches',
        'person' => 'people',
        'sex' => 'sexes'
    ];

    /**
    * Inflector words that are not countable
    *
    * @var array
    */
    private static $uncountable = [
        'equipment',
        'information',
        'research',
        'series',
        'news',
        'weather'
    ];

    /**
     * Converts a word to purual form.
     *
     * @param string $singular apple,orange,banana
     * @return string $plural
     */
    public static function plural(string $singular) : string
    {
        if (isset(self::$cache['plural'][$singular])) {
            return self::$cache['plural'][$singular];
        }

        if (isset(self::$irregular[$singular])) {
            return self::$cache['plural'][$singular] = self::$irregular[$singular];
        }
  
        $key = array_search($singular, self::$uncountable);
        if ($key !== false) {
            return self::$cache['plural'][$singular] = self::$uncountable[$key];
        }
     
        // always finds since last rule just adds an s
        foreach (self::$plural as $pattern => $replacement) {
            if (preg_match($pattern, $singular)) {
                self::$cache['plural'][$singular] = preg_replace($pattern, $replacement, $singular);
                break;
            }
        }

        return self::$cache['plural'][$singular];
    }

    /**
     * Converts a word to singular form.
     *
     * @param string $plural apples,oranges,bananas
     * @return string $singular
     */
    public static function singular(string $plural) : string
    {
        if (isset(self::$cache['singular'][$plural])) {
            return self::$cache['singular'][$plural];
        }

        $key = array_search($plural, self::$irregular);
        if ($key !== false) {
            return self::$cache['singular'][$plural] = $key;
        }
    
        $key = array_search($plural, self::$uncountable);
        if ($key !== false) {
            return self::$cache['singular'][$plural] = self::$uncountable[$key];
        }

        foreach (self::$singular as $pattern => $replacement) {
            if (preg_match($pattern, $plural)) {
                return self::$cache['singular'][$plural] = preg_replace($pattern, $replacement, $plural);
            }
        }

        return $plural;
    }

    /**
     * Converts an underscored word to mixed CamelCase
     *
     * @param string $underscoredWord studly_caps
     * @return string lowerCamelCase
     */
    public static function studlyCaps(string $underscoredWord) : string
    {
        if (isset(self::$cache['studlyCaps'][$underscoredWord])) {
            return self::$cache['studlyCaps'][$underscoredWord];
        }

        return self::$cache['studlyCaps'][$underscoredWord] = str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredWord)));
    }

    /**
     * Converts an underscored word to camelCase.
     *
     * @param string $underscoredWord camel_case
     * @return string CamelCase
     */
    public static function camelCase(string $underscoredWord) : string
    {
        if (isset(self::$cache['camelCase'][$underscoredWord])) {
            return self::$cache['camelCase'][$underscoredWord];
        }

        return self::$cache['camelCase'][$underscoredWord] = lcfirst(self::studlyCaps($underscoredWord));
    }

    /**
     * Undersores a StudlyCased word.
     *
     * @param string $studlyCasedWord StudlyCasedWord e.g. UserEmail
     * @return string $underscored_word
     */
    public static function underscored(string $studlyCasedWord) : string
    {
        if (isset(self::$cache['underscore'][$studlyCasedWord])) {
            return self::$cache['underscore'][$studlyCasedWord];
        }

        return self::$cache['underscore'][$studlyCasedWord] = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $studlyCasedWord));
    }

    /**
     * Takes a studly cased word word and underscores it, then converts to plural. Used for getting the table name
     * from a model name.
     *
     * @param string $studlyCasedWord
     * @return string $underscored
     */
    public static function tableName(string $studlyCasedWord) : string
    {
        if (isset(self::$cache['tableName'][$studlyCasedWord])) {
            return self::$cache['tableName'][$studlyCasedWord];
        }

        return self::$cache['tableName'][$studlyCasedWord] = self::plural(self::underscored($studlyCasedWord));
    }

    /**
     * Converts a table name into a class name. E.g. user_emails -> UserEmail
     *
     * @param string $table contact_actitvities
     * @return string $className ContactActivities
     */
    public static function className(string $table) : string
    {
        if (isset(self::$cache['className'][$table])) {
            return self::$cache['className'][$table];
        }

        return self::$cache['className'][$table] = self::studlyCaps(Inflector::singular($table));
    }

    /**
     * Changes a underscored word into human readable. contact_manager -> Contact Manager
     *
     * @param string $underscoredWord contact_manager
     * @return string $result Contact Manger
     */
    public static function human(string $underscoredWord) : string
    {
        if (isset(self::$cache['human'][$underscoredWord])) {
            return self::$cache['human'][$underscoredWord];
        }

        return self::$cache['human'][$underscoredWord] = ucwords(str_replace('_', ' ', $underscoredWord));
    }

    /**
     * Add user defined rules for the inflector.
     *
     * Inflector::rules('singular',[
     *    '/(quiz)zes$/i' => '\\1' // regex or string
     *    ]);
     *
     * Inflector::rules('plural',[
     *    '/(quiz)$/i' => '\1zes' // regex or string
     *    ]);
     *
     * Inflector::rules('uncountable',['sheep']); // string only
     *
     * Inflector::rules('irregular',[
     *    'child' => 'children' // string only
     *    ]);
     *
     * @param string $type  singular, plural, irregular, uncountable
     * @param array  $rules Singular and plural accept both regex patterns and strings, whilst irregular and uncountable
     * are string only.
     *
     *   A regex pattern [regexFindPattern => regexReplacementPattern] e.g ['/(quiz)$/i' => '\1zes']
     *
     * @return void
     */
    public static function rules(string $type, array $rules) : void
    {
        if (! in_array($type, ['singular','plural','irregular','uncountable'])) {
            throw new InvalidArgumentException(sprintf('Invalid rule type %s', $type));
        }
        foreach ($rules as $find => $replace) {
            if ($type === 'uncountable') {
                static::$uncountable[] = $replace;
                continue;
            }
            if ($find[0] !== '/' and $type !== 'irregular') {
                $find = '/^' . $find . '$/i';
            }
            static::$$type = [$find => $replace] + static::$$type;
        }
    }
}
