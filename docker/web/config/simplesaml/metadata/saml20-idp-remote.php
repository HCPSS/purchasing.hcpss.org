<?php
/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote 
 */

/*
 * Guest IdP. allows users to sign up and register. Great for testing!
 */
$metadata['https://hcpss.me/saml/saml2/idp/metadata.php'] = array (
    'metadata-set' => 'saml20-idp-remote',
    'entityid' => 'https://hcpss.me/saml/saml2/idp/metadata.php',
    'SingleSignOnService' =>
    array (
        0 =>
        array (
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://hcpss.me/saml/saml2/idp/SSOService.php',
        ),
    ),
    'SingleLogoutService' =>
    array (
        0 =>
        array (
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://hcpss.me/saml/saml2/idp/SingleLogoutService.php',
        ),
    ),
    'certData' => 'MIIEjjCCA3agAwIBAgIJALwsHIPTf/bBMA0GCSqGSIb3DQEBBQUAMIGKMQswCQYDVQQGEwJVUzERMA8GA1UECBMITWFyeWxhbmQxFjAUBgNVBAcTDUVsbGljb3R0IENpdHkxKzApBgNVBAoTIkhvd2FyZCBDb3VudHkgUHVibGljIFNjaG9vbCBTeXN0ZW0xCzAJBgNVBAsTAklUMRYwFAYDVQQDEw1zYW1sLmhjcHNzLm1lMCAXDTEyMDMxNjEzMTIwNloYDzIxMTIwMzE2MTMxMjA2WjCBijELMAkGA1UEBhMCVVMxETAPBgNVBAgTCE1hcnlsYW5kMRYwFAYDVQQHEw1FbGxpY290dCBDaXR5MSswKQYDVQQKEyJIb3dhcmQgQ291bnR5IFB1YmxpYyBTY2hvb2wgU3lzdGVtMQswCQYDVQQLEwJJVDEWMBQGA1UEAxMNc2FtbC5oY3Bzcy5tZTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALd7h0jmt8VeI1kninJLqESMmPF6yOUPPv7u478wrS3TRd7OS2Y4UKspw61VxEU8u28q+qFKuMxSBn0vRLEI797w0HNQrsZLPQT8PUTOkSoJhWUc9faFySc7+NC4a2R+3nTb3WxMw0RqHhxDNTQqCBa3dXFPKWPuXC4qgyRxDXT8B3g81rm6oAfLjBLqx53DN3JZftdI0wUn9ijTrGq5hF9CMa5dH/mx4xZp70DGIan5hBkgvJgl63vpRechyXg3uPbICOfl68MPvbf4vAPq0iukBFNx6QC1QQB61/2J0haqQP4HxEuHDmdwHrKVdOEhKOX1UhMkqb1WCS0IGp+MnCcCAwEAAaOB8jCB7zAdBgNVHQ4EFgQUhbHhpDO5sLOzFSHhyGSpP2zs7Kwwgb8GA1UdIwSBtzCBtIAUhbHhpDO5sLOzFSHhyGSpP2zs7KyhgZCkgY0wgYoxCzAJBgNVBAYTAlVTMREwDwYDVQQIEwhNYXJ5bGFuZDEWMBQGA1UEBxMNRWxsaWNvdHQgQ2l0eTErMCkGA1UEChMiSG93YXJkIENvdW50eSBQdWJsaWMgU2Nob29sIFN5c3RlbTELMAkGA1UECxMCSVQxFjAUBgNVBAMTDXNhbWwuaGNwc3MubWWCCQC8LByD03/2wTAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4IBAQCgWw1++lQlX4ocPhjVvC/Jxkg1a35bhxezDfcUZb/cqKa9Itg2UahsLSMBeNDaI2paBm/NZY/Ww3XhVLzNb3RsoIwGA2oD777R/tQ41n3cCrfjU5ymOgljuv+Nbnxo9JLIS+TJKPxjav46t6foXRVOfrVq2IF1yM+qUDTJU1d/l4499li3MU32bZgfplTT+qzf5aGx5ZOwAOsoHGAwcoCEEBdQB+7IH7AEfatvQBc3mYpt6360scLWOvfamyr1nMJS2FgIBg5XjzA5xA0WAAt5Q/jhCH/YmKJ2JpKC4aGReT9+Kt/aHkfj40MCoSuHCEP4xfja7qWzgVvKnMx0crPi',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
);
