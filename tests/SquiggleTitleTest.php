<?php

declare(strict_types=1);

final class SquiggleTitleTest extends CJW_Brummen_TestCase
{
    public function testWrapsThePartAfterThePipe(): void
    {
        $this->assertSame(
            'Zo ziet kamp <span class="squiggle">eruit</span>',
            cjw_brummen_squiggle_title('Zo ziet kamp |eruit')
        );
    }

    public function testReturnsAPlainTitleUnwrapped(): void
    {
        $this->assertSame('Bedankt!', cjw_brummen_squiggle_title('Bedankt!'));
    }

    public function testEscapesMarkupInBothHalves(): void
    {
        $this->assertSame(
            '&lt;b&gt;a&lt;/b&gt;<span class="squiggle">&lt;i&gt;b&lt;/i&gt;</span>',
            cjw_brummen_squiggle_title('<b>a</b>|<i>b</i>')
        );
    }

    public function testOnlyTheFirstPipeSplits(): void
    {
        $this->assertSame(
            'a<span class="squiggle">b|c</span>',
            cjw_brummen_squiggle_title('a|b|c')
        );
    }
}
