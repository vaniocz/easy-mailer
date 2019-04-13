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
        ], EmailAddresses::fromString("John Doe <john@doe.com>\nhomer.simpson@fox.com"));
    }

    function test_email_address_list_with_different_newlines_parsing()
    {
        $this->assertEquals([
            new EmailAddress('john@doe.com', 'John Doe'),
            new EmailAddress('homer.simpson@fox.com'),
            new EmailAddress('foo@bar.baz'),
        ], EmailAddresses::fromString("John Doe <john@doe.com>\r\n homer.simpson@fox.com\r foo@bar.baz"));
    }

    function test_email_address_list_with_leading_and_trailing_newlines_parsing()
    {
        $expected = [new EmailAddress('john@doe.com', 'John Doe')];
        $this->assertEquals($expected, EmailAddresses::fromString("John Doe <john@doe.com>\r\n"));
        $this->assertEquals($expected, EmailAddresses::fromString("\nJohn Doe <john@doe.com>"));
    }

    function test_email_address_list_with_leading_and_trailing_whitespace_parsing()
    {
        $expected = [new EmailAddress('john@doe.com', 'John Doe')];
        $this->assertEquals($expected, EmailAddresses::fromString(' John Doe <john@doe.com> '));
    }

    function test_email_address_list_with_multiple_newlines_parsing()
    {
        $this->assertEquals([
            new EmailAddress('john@doe.com', 'John Doe'),
            new EmailAddress('homer.simpson@fox.com'),
        ], EmailAddresses::fromString("\n\r\nJohn Doe <john@doe.com>\n\n\nhomer.simpson@fox.com\r\r\r"));
    }
}
