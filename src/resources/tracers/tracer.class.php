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
 * Class to manage the tracer action
 *
 * @author David Herney <davidherney@gmail.com>
 * @package CyQ.Api
 * @copyright  2018 Congo y Quima Project
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero GPL v3 or later
 */
class Tracer extends BoAComplexObject {

    public function __construct($id = 0) {

        parent::__construct('tracers', $id);

    }

    public static function decipher($traceinfo) {

        $driverdata = Restos::$DefaultRestGeneric->getDriverData("registries");

        $privatekey = file_get_contents($driverdata->Properties->PrivateKeyPath);

        $traceinfo = base64_decode($traceinfo);

        // Decrypt the data using the private key and store the results in $decrypted.
        if (!openssl_private_decrypt($traceinfo, $decrypted, $privatekey)) {
            return null;
        }

        return json_decode($decrypted);
    }

}
