<?php

namespace Elegasoft\Cipher\Tests\Feature;

use Elegasoft\Cipher\Ciphers\Base62Cipher;
use Elegasoft\Cipher\Ciphers\Base96Cipher;
use Elegasoft\Cipher\Tests\TestCase;
use Illuminate\Support\Str;

class ManualTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->markTestSkipped();
    }

    /** @test */
    public function readMeExamples()
    {
        $text = 'In cryptography, a substitution cipher is a method of encrypting by which units of plaintext are replaced with ciphertext, according to a fixed system; the "units" may be single letters (the most common), pairs of letters, triplets of letters, mixtures of the above, and so forth. The receiver deciphers the text by performing the inverse substitution. Substitution of single letters separately — simple substitution — can be demonstrated by writing out the alphabet in some order to represent the substitution. It is a cipher key, and it is also called a substitution alphabet.For a simple substitution cipher, the set of all possible keys is the set of all possible permutations. Thus, for the English alphabet, the number of keys is 26! (factorial of 26), which is about 403*10^{24}. Because of this, if you want to decipher the text without knowing the key, the brute force approach is out of the question.However, the simple substitution cipher is considered a weak cipher because it is vulnerable to cryptoanalysis. First of all, substitution does not change the letters\' frequencies, so if you have a decent amount of enciphered text and you know the language it was written in, you can try frequency analysis. For example, the most common letter in the English language is E, so, most common letter in the encrypted text is probable the E substitution. The analyst also looks for bigrams and trigrams frequencies because some unigram frequencies are too close to each other to rely on them. Using frequencies, analysts can create trial keys and test them to see if they reveal some words and phrases in the encrypted text. But this manual approach is time-consuming, so the goal of an automated solution is to exclude humans from the process of breaking the cipher. And it is possible due to another simple substitution cipher vulnerability, known as Utility of Partial Solution. In other words, if there are many pairs of keys in the keyspace where the decryption of the ciphertext by the key more similar to the correct key more closely resembles the plaintext than the decryption of the ciphertext by the other key, the cipher has Utility of Partial Solutions... If there is a correlation between the degree to which a key resembles the correct key and the degree to which that key\'s decryption of the ciphertext resembles the plaintext, it should be possible to search the keyspace efficiently by quickly discarding keys that are "worse" than whatever key is the closest match at any moment, climbing ever closer to the optimal key without knowing it initially. These keyspaces can be searched via Stochastic Optimization Algorithms.The tricky part here is how you can measure if one key is "worse" than another. We need text fitness to address this, which gives us some score on how the given text looks like typical English text. There are different approaches, and I\'ve tried this and that, but one which worked for me is outlined here: Text fitness (version 3). In short, it uses the sum of log probabilities of quadgrams and compares the sum with the sum for the "normal" English text (created as the sum of log probabilities of the most often English quadgrams). Here I\'d like to thank Jens Guballa (site), author of another substitution solver, who kindly gives me a hint that text fitness function should be "normalized." The implementation below uses a genetic algorithm to search for the correct key. If it fails, you can repeat a couple of times (each time it starts from a set of random keys as an initial generation) or tweak the settings, for example, increase the number of generations. Just click the Details to reveal additional settings. In this mode, the calculator also displays the best key in each generation, which is quite curious to watch.If you see that the found key is close to the correct one but misses a couple of letters, you may use Substitution cipher tool to manually test the keys.';

        $cipher = new Base62Cipher(config('cipher.keys.base62'));

        dd([
            'base62:bat'                   => $cipher->encipher('bat'),
            'base62:cat'                   => $cipher->encipher('cat'),
            'base62:hat'                   => $cipher->encipher('hat'),
            'base62:mat'                   => $cipher->encipher('mat'),
            'base62:hide-this-number-1111' => $cipher->encipher('hide-this-number-1111'),
            'base62:aaaaaaaa'              => $cipher->encipher('aaaaaaaa'),
            'base62:aaaaaaab'              => $cipher->encipher('aaaaaaab'),
            'base62:aaaaaaac'              => $cipher->encipher('aaaaaaac'),
            'base62:aaaaaaad'              => $cipher->encipher('aaaaaaad'),
            'base62:aaaaaaaa (again)'      => $cipher->encipher('aaaaaaaa'),
            'base62:baaaaaaa'              => $cipher->encipher('baaaaaaa'),
            'base62:caaaaaaa'              => $cipher->encipher('caaaaaaa'),
            'base62:daaaaaaa'              => $cipher->encipher('daaaaaaa'),
            'base62:eaaaaaaa'              => $cipher->encipher('eaaaaaaa'),
            'base62:faaaaaaa'              => $cipher->encipher('faaaaaaa'),
            'base62:gaaaaaaa'              => $cipher->encipher('gaaaaaaa'),
        ]);
        $code62 = $cipher->encipher($text);
//        $code62 = $cipher->encipher('hide-this-number-1111');
        $cipher = new Base96Cipher(config('cipher.keys.base96'));
        $code96 = $cipher->encipher('hide-this-number-1111');
        dd($code62, $code96);
    }

    public function setCipher(): void
    {
        $this->encoder = new Base62Cipher(config('cipher.keys.base62'));
        $this->decoder = clone $this->encoder;
    }

    public function it_has_perfect_cipher_accuracy()
    {
    }

    public function it_can_work_on_a_specific_problem()
    {
        $this->markTestSkipped();
        $test = [];
        foreach (range(1000, 1010) as $id)
        {
            $padded = str_pad($id, 6, '0', STR_PAD_LEFT);
            $test[] = [
                'id'      => $id,
                '$padded' => $padded,
                'encoded' => $encoded = $this->encoder->encipher(Str::reverse($padded)),
                'decoded' => (int)Str::reverse($this->decoder->decipher($encoded)),
            ];
        }
        dd($test);
    }
}
