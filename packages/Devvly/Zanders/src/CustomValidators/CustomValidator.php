<?php

namespace Devvly\Zanders\CustomValidators;

interface CustomValidator {

    public function __construct(array $data);

    public function execute();

}
