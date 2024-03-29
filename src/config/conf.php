<?php

date_default_timezone_set('Asia/Tokyo');

// ################################################################################
//                                  LOGファイル
// ################################################################################
// Windows環境
CONST LOG_FILE_PATH = "C:\\Users\\user\\Dropbox\\tag_admin\\logs\\";
CONST TAG_FILE_PATH = "C:\\Users\\user\\Dropbox\\tag_admin\\tagFiles\\";

// MAC環境
// CONST LOG_FILE_PATH = "/Users/moeka/Dropbox/tag_admin/logs";
// 家のパソコン
// CONST LOG_FILE_PATH = "C:\\Users\\Gamer\\Dropbox\\tag_admin\\logs\\";
// CONST TAG_FILE_PATH = "C:\\Users\\Gamer\\Dropbox\\tag_admin\\tagFiles\\";

// 本番環境(x server)
// CONST LOG_FILE_PATH = "/home/aacr/aacr.xsrv.jp/public_html/tag_admin/logs/";
// CONST TAG_FILE_PATH = "/home/aacr/aacr.xsrv.jp/public_html/tag_admin/tagFiles/";
// CONST TAG_LP_FILE_PATH = "/home/aacr/aacr.xsrv.jp/public_html/tag_admin/tagErrorLog/";

// 本番環境
// CONST LOG_FILE_PATH = "/www/wwwroot/tag-admin.info/logs/";
// CONST TAG_FILE_PATH = "/www/wwwroot/tag-admin.info/tagFiles/";
// CONST TAG_LP_FILE_PATH = "/www/wwwroot/tag-admin.info/tagErrorLog/";


CONST PATH = "/tag_admin/";
// CONST PATH = "/";

CONST PATH_INDEX = "https://tag-tracker.biz/index.js?id=";
CONST PATH_RD = "https://tag-tracker.biz/rd.js?id=";



// ################################################################################
// 　エラーメッセージ
// ################################################################################


CONST ERROR_INVALID_VALUE = "ユーザー名またはパスワードが違います";
CONST ERROR_EMPTY_VALUE = "ユーザー名とパスワードを入力してください";
CONST ERROR_TEXT = "システムエラーが発生しました: お手数ですが管理者までお問い合わせください。";
CONST SECURITY_ERROR = "セキュリティエラー：不正なリクエストが検出されました。お手数ですが、もう一度試してみてください。";
CONST SECURITY_ERROR_DATA = "セキュリティエラー：不正なデータが送信されました。お手数ですが、もう一度試してみてください。";
CONST SECURITY_ERROR_EMPTY_DATA = "セキュリティエラー：お手数ですが、もう一度試してみてください。";
CONST ERROR_INPUT_DOMAIN_ERROR = "必須項目をすべて入力してください";
CONST SUCCESS_ADD_DOMAIN = "ドメイン追加に成功しました";
CONST SUCCESS_UPDATE_DOMAIN = "ドメイン更新に成功しました";
CONST SUCCESS_DELETE_DOMAIN = "ドメイン削除に成功しました";
CONST SUCCESS_DEACTIVATE_TAG = "タグ削除に成功しました";
CONST SUCCESS_ADD_TAG = "タグの追加に成功しました";
CONST SUCCESS_UPDATE_TAG = "タグの更新に成功しました";
CONST SUCCESS_DEACTIVATE_DOMAIN = "ドメインのステータスの更新に成功しました";
CONST DOMAIN_ALREADY_EXISTED = "入力されたドメインは既に登録されています。";
CONST SUCCESS_CATEGORY_CREATE = "カテゴリー追加に成功しました";
CONST SUCCESS_CATEGORY_EDIT = "カテゴリー更新に成功しました";

CONST ERROR_CODE_LOGIN = 1;
CONST ERROR_CODE_DATABASE = 2;
CONST ERROR_CODE_FETCH = 3;
// CONST ERROR_CODE_SECURITY= 4;
CONST ERROR_CODE_INDEX= 4;


const VALIDATION_ERROR_CREATE = "create";
const VALIDATION_ERROR_EDIT = "edit";



CONST PASSWORD_MAXLENGTH = 6;

