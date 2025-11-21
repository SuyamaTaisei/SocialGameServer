<?php

return [
    //ユーザー関連
    'USER_NAME' => 'タロウ',
    'DEFAULT_STAMINA' => 100,

    //スタミナ関連
    'STAMINA_RECOVERY_SECOND' => 18, //スタミナが回復するのにかかる秒数
    'STAMINA_RECOVERY_VALUE' => 1, //1回のスタミナ回復量

    //ウォレット関連
    'COIN_AMOUNT' => 100,
    'GEM_FREE_AMOUNT' => 100,
    'GEM_PAID_AMOUNT' => 100,

    //レスポンス関連
    'RESPONSE_SUCCESS' => 0,
    'ERRCODE_VALIDATION' => 100,
    'ERRCODE_MASTER_VERSION' => 200,
    'ERRCODE_LOGIN_SESSION' => 403,

    //アカウント登録時エラー
    'ERRCODE_REGISTER' => -1,

    //支払い時エラー
    'ERRCODE_NOT_PAYMENT' => 510,
    'ERRCODE_LIMIT_WALLETS' => 511,

    //マスタデータ関連
    'MASTER_DATA_VERSION' => 1, //マスターデータのバージョン
    'ERRCODE_MASTER_DATA_UPDATE' => 0,
];