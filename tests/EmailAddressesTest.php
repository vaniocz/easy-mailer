<?php
namespace Vanio\EasyMailer\Tests;

use PHPUnit\Framework\TestCase;
use Vanio\EasyMailer\EmailAddress;
use Vanio\EasyMailer\EmailAddresses;

class EmailAddressesTest extends TestCase
{
    function test_email_address_list_parsing()
    {
        $this->assertEquals([
            new EmailAddress('john@doe.com', 'John Doe'),
            new EmailAddress('homer.simpson@fox.com'),
        ], EmailAddresses::fromString('John Doe <john@doe.com>, homer.simpson@fox.com'));
    }

    function test_email_address_list_with_different_separators_parsing()
    {
        $this->assertEquals([
            new EmailAddress('john@doe.com', 'John Doe'),
            new EmailAddress('homer.simpson@fox.com'),
            new EmailAddress('foo@bar.baz'),
        ], EmailAddresses::fromString('John Doe <john@doe.com>, homer.simpson@fox.com; foo@bar.baz'));
    }

    function test_email_address_list_with_trailing_separator_parsing()
    {
        $expected = [new EmailAddress('john@doe.com', 'John Doe')];
        $this->assertEquals($expected, EmailAddresses::fromString('John Doe <john@doe.com>,'));
        $this->assertEquals($expected, EmailAddresses::fromString('John Doe <john@doe.com>;'));
    }

    function test_email_address_list_with_separator_in_quoted_name_parsing()
    {
        $this->assertEquals([
            new EmailAddress('john@doe.com', 'Doe, John'),
            new EmailAddress('home.simpson@fox.com'),
            new EmailAddress('foo@bar.baz', '"Foo"; Bar'),
        ], EmailAddresses::fromString('"Doe, Jo""hn" <john@doe.com>, home.simpson@fox.com; "\"Foo\"; Bar" <foo@bar.baz>'));
    }

    function test_email_address_list_with_separator_in_quoated_name_and_quoted_backslashes_parsing()
    {
        $this->assertEquals([
            new EmailAddress('john@doe.com', '\"John", "Doe"'),
            new EmailAddress('foo@bar.baz', 'Foo "Bar" \A,B')
        ], EmailAddresses::fromString('\\"\"John\", \"Doe\" <john@doe.com>", F"o"o \"Bar\" \\"A,B" <foo@bar.baz>'));
    }
}
