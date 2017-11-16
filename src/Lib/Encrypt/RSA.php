<?php
namespace Yurun\PaySDK\Lib\Encrypt;

class RSA
{
	public static function encryptPrivate($data, $key)
	{
		$key = "-----BEGIN RSA PRIVATE KEY-----\n{$key}\n-----END RSA PRIVATE KEY-----";
		openssl_sign($data, $sign, $key, OPENSSL_ALGO_SHA1);
		return $sign;
	}

	public static function encryptPrivateFromFile($data, $fileName)
	{
		$key = file_get_contents($fileName);
		$res = openssl_get_privatekey($key);
		if(!$res)
		{
			throw new \Exception('私钥文件格式错误');
		}
		openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA1);
		openssl_free_key($res);
		return $sign;
	}

	public static function verifyPublic($data, $key, $sign)
	{
		$key = "-----BEGIN PUBLIC KEY-----\n{$key}\n-----END PUBLIC KEY-----";
		return 1 === openssl_verify($data, $sign, $key, OPENSSL_ALGO_SHA1);
	}

	public static function verifyPublicFromFile($data, $fileName, $sign)
	{
		$key = file_get_contents($fileName);
		$res = openssl_get_publickey($key);
		if(!$res)
		{
			throw new \Exception('公钥文件格式错误');
		}
		$result = openssl_verify($data, $sign, $res, OPENSSL_ALGO_SHA1);
		openssl_free_key($res);
		return 1 === $result;
	}
}