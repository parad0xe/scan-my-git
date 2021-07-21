<?php

namespace App\Classes\ModuleProxy\Nodes\Input;

interface InputNodeInterface {
    public function initNode();

    public function getHTMLLabel(): string;

    public function getHTMLField(int $module_id): string;

    public function buildHTML(int $module_id, array $attributes = []): string;

    public function getType(): string;

    public function setValue(int | string | null $value): InputNodeInterface;

    public function validateValue(int | string | null $value): bool;
}
