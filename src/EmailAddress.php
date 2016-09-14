<?php
namespace Vanio\EasyMailer;

use InvalidArgumentException;

/**
 * Represents a single e-mail address.
 */
class EmailAddress
{
    /**
     * E-mail address.
     *
     * @var string
     */
    private $email;

    /**
     * Display name.
     *
     * @var string|null
     */
    private $name;

    /**
     * Set the e-mail address and display name.
     *
     * @param string $email E-mail address.
     * @param string|null $name Display name.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $email, string $name = null)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid e-mail address.', $email));
        }

        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Create a new e-mail address by parsing the given string.
     *
     * @param string $emailAddress The string to be parsed.
     *
     * @return static Newly created e-mail address.
     *
     * @throws InvalidArgumentException
     */
    public static function fromString(string $emailAddress): self
    {
        if (!preg_match('/^([^><]+?)\s*(?:<([^><]+)>)?$/', $emailAddress, $matches)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid e-mail address.', $emailAddress));
        }

        return isset($matches[2]) ? new static($matches[2], $matches[1]) : new static($matches[1]);
    }

    /**
     * Get the e-mail address.
     *
     * @return string E-mail address.
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * Get the display name.
     *
     * @return string|null Display name.
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Get a string representation of this e-mail address.
     *
     * @return string String representation of this e-mail address.
     */
    public function __toString(): string
    {
        return $this->name !== null
            ? sprintf('%s <%s>', $this->name, $this->email)
            : $this->email;
    }
}
