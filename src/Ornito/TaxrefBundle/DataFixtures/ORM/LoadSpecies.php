<?php

namespace Ornito\TaxrefBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ornito\TaxrefBundle\Entity\Species;

class LoadSpecies implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Open TAXREF file in readonly
        $handle = fopen(__DIR__ . "/../../../../../web/TAXREFv11.txt", "r");
        $stock = [];

        while (!feof($handle)) { // Read file until the end
            $findMe = 'Chordata';
            $buffer = fgets($handle); // Gets line from file pointer
            $pos1 = strpos($buffer, $findMe); // Find the position of "Chordata" occurrence
            if ($pos1 != false) { // Check if occurrence is found
                $class = 'Aves';
                $pos2 = strpos($buffer, $class); // Find the position of "Aves" occurrence
                if ($pos2 != false) { // Check if occurrence is found
                    $tab = str_replace("\r\n","\t", $buffer); // Replace the carriage return "\r" and the new line "\n" of the end string by a tab "\t" to be parse after
                    $tab = explode("\t", $tab); // Split the string
                    if (!empty($tab['39'])) {
                        // Check if the scientific bird's name or the bird's name are in the array $stock
                        if (in_array($tab['14'], $stock) || in_array($tab['19'], $stock)) {
                            // We don't create the bird to avoid duplicates (already in bdd)
                        } else {
                            if ((!empty($tab['19']))) {
                                // We push the bird's name into an array if different from empty
                                array_push($stock, $tab['19']);
                            }
                            // We push the scientific bird's name into an array
                            array_push($stock, $tab['14']);
                            // Create a Species object and gets only columns we need
                            $bird = new Species($tab['3'], $tab['4'], $tab['14'], $tab['15'], $tab['19'], $tab['20'], $tab['22'], $tab['39']);
                            $manager->persist($bird);
                        }
                    }
                }
            }
        }
        fclose($handle); // Closes an open file pointer
        $manager->flush();
    }
}