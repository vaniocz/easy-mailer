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
        $pattern = '~\s*((?:^|\s*[,;])\s*(?:".*?(?<!\\\\)(?:\\\\\\\\)*"\s*(?=\<))?)\s*~';
        $emails = [];
        $email = '';

        foreach (preg_split($pattern, $emailAddresses, -1, PREG_SPLIT_DELIM_CAPTURE) as $type => $token) {
            $token = trim($token);

            if ($type % 2) {
                if ($email !== '') {
                    $emails[] = EmailAddress::fromString($email);
                }

                $token = ltrim($token, ',; ');

                if ($token === '') {
                    $email = '';
                } else {
                    $email = self::unescape(substr($token, 1, -1));
                }
            } else {
                $email .= $token;
            }
        }

        if ($email !== '') {
            $emails[] = EmailAddress::fromString($email);
        }

        return $emails;
    }

    private static function unescape(string $name): string
    {
        $string = '';
        $length = strlen($name);

        for ($i = 0; $i < $length; $i++) {
            $string .= $name[$i] === '\\' && $length - 1 > $i && ($name[$i + 1] === '\\' || $name[$i + 1] === '"')
                ? $name[++$i]
                : $name[$i];
        }

        return $string;
    }
}
