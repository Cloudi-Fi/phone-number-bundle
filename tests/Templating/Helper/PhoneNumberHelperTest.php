<?php

/*
 * This file is part of the Symfony2 PhoneNumberBundle.
 *
 * (c) University of Cambridge
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Misd\PhoneNumberBundle\Tests\Templating\Helper;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Misd\PhoneNumberBundle\Exception\InvalidArgumentException;
use Misd\PhoneNumberBundle\Templating\Helper\PhoneNumberHelper;
use PHPUnit\Framework\TestCase;

/**
 * Phone number templating helper test.
 */
class PhoneNumberHelperTest extends TestCase
{
    protected $phoneNumberUtil;
    protected $helper;

    public function setUp(): void
    {
        $this->phoneNumberUtil = $this->prophesize(PhoneNumberUtil::class);
        $this->helper = new PhoneNumberHelper($this->phoneNumberUtil->reveal());
    }

    /**
     * @dataProvider processProvider
     */
    public function testProcess($format, $expectedFormat)
    {
        $phoneNumber = $this->prophesize(PhoneNumber::class);
        $this->phoneNumberUtil->format($phoneNumber->reveal(), $expectedFormat)->shouldBeCalledTimes(1);

        $this->helper->format($phoneNumber->reveal(), $format);
    }

    /**
     * 0 => Format
     * 1 => Expected format.
     */
    public function processProvider()
    {
        yield [PhoneNumberFormat::NATIONAL, PhoneNumberFormat::NATIONAL];
        yield ['NATIONAL', PhoneNumberFormat::NATIONAL];
    }

    public function testProcessInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        $phoneNumber = $this->prophesize(PhoneNumber::class);

        $this->helper->format($phoneNumber->reveal(), 'foo');
    }
}
