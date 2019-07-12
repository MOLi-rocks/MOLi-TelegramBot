<?php

namespace MOLiBot\DataSources;

interface SourceInterface
{
    public function getContent() : array;
}