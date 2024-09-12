<?php

namespace _PhpScoper3234cdc49fbb;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use _PhpScoper3234cdc49fbb\Symfony\Polyfill\Intl\Normalizer as p;

if (!\function_exists('normalizer_is_normalized')) {
    function normalizer_is_normalized($string, $form = \_PhpScoper3234cdc49fbb\Symfony\Polyfill\Intl\Normalizer\Normalizer::FORM_C)
    {
        return \_PhpScoper3234cdc49fbb\Symfony\Polyfill\Intl\Normalizer\Normalizer::isNormalized($string, $form);
    }
}
if (!\function_exists('normalizer_normalize')) {
    function normalizer_normalize($string, $form = \_PhpScoper3234cdc49fbb\Symfony\Polyfill\Intl\Normalizer\Normalizer::FORM_C)
    {
        return \_PhpScoper3234cdc49fbb\Symfony\Polyfill\Intl\Normalizer\Normalizer::normalize($string, $form);
    }
}
