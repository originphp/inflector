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

namespace Origin\Test\Inflector;

use InvalidArgumentException;
use Origin\Inflector\Inflector;

class InflectorTest extends \PHPUnit\Framework\TestCase
{
    public function testPlural()
    {
        // Test rules
        $this->assertEquals('apples', Inflector::plural('apple'));
        $this->assertEquals('companies', Inflector::plural('company'));
        $this->assertEquals('branches', Inflector::plural('branch'));
        $this->assertEquals('businesses', Inflector::plural('business'));
        $this->assertEquals('backslashes', Inflector::plural('backslash'));
        $this->assertEquals('mailboxes', Inflector::plural('mailbox'));
        $this->assertEquals('responses', Inflector::plural('response'));

        // Test fit
        $this->assertEquals('statuses', Inflector::plural('status'));
        $this->assertEquals('employees', Inflector::plural('employee'));
        $this->assertEquals('processes', Inflector::plural('process'));
        $this->assertEquals('patches', Inflector::plural('patch'));
        $this->assertEquals('cases', Inflector::plural('case'));

        $this->AssertEquals('-----s', Inflector::plural('-----'));
    }

    public function testSingular()
    {
        $this->assertEquals('apple', Inflector::singular('apples'));
        $this->assertEquals('company', Inflector::singular('companies'));
        $this->assertEquals('branch', Inflector::singular('branches'));
        $this->assertEquals('business', Inflector::singular('businesses'));
        $this->assertEquals('backslash', Inflector::singular('backslashes'));
        $this->assertEquals('mailbox', Inflector::singular('mailboxes'));
        $this->assertEquals('response', Inflector::singular('responses'));
        $this->assertEquals('', Inflector::singular(''));

        $this->assertEquals('status', Inflector::singular('statuses'));
        $this->assertEquals('employee', Inflector::singular('employees'));
        $this->assertEquals('process', Inflector::singular('processes'));
        $this->assertEquals('patch', Inflector::singular('patches'));
        $this->assertEquals('case', Inflector::singular('cases'));
        $this->AssertEquals('-----', Inflector::singular('-----s'));
        $this->AssertEquals('-----', Inflector::singular('-----'));
    }

    public function testIrregular()
    {
        $this->assertEquals('child', Inflector::singular('children'));
        $this->assertEquals('children', Inflector::plural('child'));
    }

    public function testUncountable()
    {
        $this->assertEquals('equipment', Inflector::singular('equipment'));
        $this->assertEquals('equipment', Inflector::plural('equipment'));
    }

    public function testStudlyCaps()
    {
        $this->assertEquals('CamelCase', Inflector::studlyCaps('camel_case'));
    }

    public function testUnderscored()
    {
        $this->assertEquals('contacts', Inflector::underscored('Contacts'));
        $this->assertEquals('leads', Inflector::underscored('leads'));
        $this->assertEquals('contact_notes', Inflector::underscored('ContactNotes'));
        $this->assertEquals('lead_notes', Inflector::underscored('leadNotes'));
    }

    public function testTableName()
    {
        $this->assertEquals('contacts', Inflector::tableName('Contact'));
        $this->assertEquals('contact_emails', Inflector::tableName('ContactEmail'));
    }

    public function testCamelCase()
    {
        $this->assertEquals('camelCase', Inflector::camelCase('camel_case'));
    }

    /**
     * @depends testSingular
     */
    public function testClassname()
    {
        $this->assertEquals('Contact', Inflector::className('contacts'));
        $this->assertEquals('ContactEmail', Inflector::className('contact_emails'));
    }

    public function testHuman()
    {
        $this->assertEquals('Contact Manager', Inflector::human('contact_manager'));
    }

    public function testAddRules()
    {
        Inflector::rules('singular', ['/^(f)ezzes$/i' => '\1ez']);
        $this->assertEquals('fez', Inflector::singular('fezzes'));

        print_r(preg_replace('/^abc$/i', '123', 'abc'));
   
        Inflector::rules('singular', ['abc' => '123']);
        $this->assertEquals('123', Inflector::singular('abc'));

        Inflector::rules('plural', ['/^(f)ez$/i' => '\1ezzes']);
        $this->assertEquals('fezzes', Inflector::plural('fez'));

        Inflector::rules('uncountable', ['foos']);
        $this->assertEquals('foos', Inflector::singular('foos'));

        Inflector::rules('irregular', ['bars' => 'bars']);
        $this->assertEquals('bars', Inflector::singular('bars'));

        $this->expectException(InvalidArgumentException::class);
        Inflector::rules('foo', ['foo' => 'bar']);
    }

    public function testCachedVersions()
    {
        $this->assertEquals('apples', Inflector::plural('apple'));
        $this->assertEquals('apple', Inflector::singular('apples'));
        $this->assertEquals('child', Inflector::singular('children'));
        $this->assertEquals('equipment', Inflector::singular('equipment'));
        $this->assertEquals('CamelCase', Inflector::studlyCaps('camel_case'));
        $this->assertEquals('contacts', Inflector::underscored('Contacts'));
        $this->assertEquals('contacts', Inflector::tableName('Contact'));
        $this->assertEquals('camelCase', Inflector::camelCase('camel_case'));
        $this->assertEquals('Contact Manager', Inflector::human('contact_manager'));
        $this->assertEquals('Contact', Inflector::className('contacts'));
    }
}
