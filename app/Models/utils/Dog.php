<?php

namespace App\Models\utils;

class Dog extends Animal implements Soundable {
    public function makeSound() {
        echo "Woof! Woof!";
    }
}
