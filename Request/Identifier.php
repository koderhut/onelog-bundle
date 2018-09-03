<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Request;

/**
 * Class Identifier
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class Identifier implements IdentifierInterface
{

    /**
     * @var string
     */
    private $identifier;

    /**
     * Identifier constructor.
     *
     * @param string $identifier
     */
    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * It will generate an instance based on the current date
     * using the format YmdHis.u
     *
     * @param string ...$salts
     *
     * @return Identifier
     */
    public static function generate(string ...$salts)
    {
        $hashData = (string) (new \DateTime())->format('Ymd.His.u');

        if (!empty($salts)) {
            $hashData .= '.' . implode('.', $salts);
        }

        return new self($hashData);
    }

    /**
     * @inheritdoc
     */
    public function identifier(): string
    {
        return $this->identifier;
    }

}
