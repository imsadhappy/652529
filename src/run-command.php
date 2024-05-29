<?php

if (isset($_ENV) && isset($_ENV['COMMAND'])) {
    new $_ENV['COMMAND']($_ENV);
} else {
    exit('Please setup $_ENV');
}
