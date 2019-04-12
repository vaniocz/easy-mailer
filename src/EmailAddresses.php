<?php
namespace Vanio\EasyMailer;

class EmailAddresses
{
    /**
     * Create a new list of e-mail addresses by parsing the given string.
     *
     * @param string $emailAddresses The string to be parsed.
     *
     * @return EmailAddress[] Newly created list of e-mail addresses.
     */
    public static function fromString(string $emailAddresses): array
    {
        return array_map(
            EmailAddress::class . '::fromString',
            preg_split('/\s*\v\s*/', trim($emailAddresses), -1, PREG_SPLIT_NO_EMPTY)
        );
    }
}
