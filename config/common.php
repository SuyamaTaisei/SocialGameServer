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

    //マスタデータ関連
    'MASTER_DATA_VERSION' => 1,
    'ERRCODE_MASTER_DATA_UPDATE' => 0,
    'ERRCODE_MASTER_VERSION' => 200,

    //成功レスポンス
    'RESPONSE_SUCCESS' => 0,

    //ログイン関連
    'ERRCODE_VALIDATION' => 100,
    'ERRCODE_LOGIN_SESSION' => 403,

    //アカウント登録関連
    'ERRCODE_REGISTER' => -1,

    //支払い関連
    'ERRCODE_NOT_PAYMENT' => 510,
    'ERRCODE_LIMIT_WALLETS' => 511,

    //ショップ関連
    'MAX_CURRENCY_VALUE' => 999999,
    'SHOP_CATEGORY_GEM' => 1001,
    'SHOP_CATEGORY_ITEM' => 1002,
    'PAYMENT_TYPE_GEM' => 'Gem',
    'PAYMENT_TYPE_COIN' => 'Coin',

    //ガチャ実行関連
    'GACHA_NORMAL_PAYMENT' => 250,
    'GACHA_TOTAL_WEIGHT' => 100000,
    'GACHA_RARITY_1000' => 1000,
    'GACHA_RARITY_2000' => 2000,
    'GACHA_RARITY_3000' => 3000,
    'GACHA_RARITY_4000' => 4000,

    //ガチャ期間関連
    'GACHA_NORMAL_PERIOD' => 1001,
];