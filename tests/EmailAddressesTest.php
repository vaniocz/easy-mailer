<?php
namespace Vanio\EasyMailer\Tests;

use PHPUnit_Framework_TestCase;
use Vanio\EasyMailer\EmailAddress;
use Vanio\EasyMailer\EmailAddresses;

class EmailAddressesTest extends PHPUnit_Framework_TestCase
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
}
