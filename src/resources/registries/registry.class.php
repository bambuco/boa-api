<?php
// This file is part of CyQ - https://github.com/boa-project
//
// CyQ is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// CyQ is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with CyQ.  If not, see <http://www.gnu.org/licenses/>.
//
// The latest code can be found at <https://github.com/boa-project/>.

//Include global dependences
Restos::using('resources.boacomplexobject');

/**
 * Class to manage the registry action
 *
 * @author David Herney <davidherney@gmail.com>
 * @package CyQ.Api
 * @copyright  2018 Congo y Quima Project
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero GPL v3 or later
 */
class Registry extends BoAComplexObject {

    public function __construct($id = 0) {

        parent::__construct('registries', $id);

    }

    public static function decipher($registryinfo) {

        $driverdata = Restos::$DefaultRestGeneric->getDriverData("registries");

        $privatekey = file_get_contents($driverdata->Properties->PrivateKeyPath);

        $registryinfo = base64_decode($registryinfo);

        // Decrypt the data using the private key and store the results in $decrypted.
        if (!openssl_private_decrypt($registryinfo, $decrypted, $privatekey)) {
            return null;
        }

        return json_decode($decrypted);
    }

    public static function cypher($info, $password) {

        if (is_object($info) || is_array($info)) {
            $info = json_encode($info);
        }

        $driverdata = Restos::$DefaultRestGeneric->getDriverData("registries");

        // Encrypt the data to $encrypted using the client password.
        $encrypted = openssl_encrypt($info, $driverdata->Properties->EncryptMethod, hex2bin(self::strToHex($password)), 0, base64_decode($driverdata->Properties->IVBase64));

        return $encrypted;
    }

    public static function getNewToken() {

        $driverdata = Restos::$DefaultRestGeneric->getDriverData("registries");
        $base = time() . $driverdata->Properties->TokenSalt . rand(0, 9999999999);

        return md5($base);
    }

    public static function strToHex($string){
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++){
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }
        return strToUpper($hex);
    }


}
