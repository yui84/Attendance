# Attendance

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:yui84/Attendance.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```

## メール認証
mailtrapを使用。  
メールボックスのIntegrationsから 「laravel 7.x and 8.x」を選択し、  
.envファイルのMAIL_MAILERからMAIL_ENCRYPTIONまでの項目をコピー＆ペーストしてください。  
MAIL_FROM_ADDRESSは任意のメールアドレスを入力してください。

## テストアカウント
**name:管理者ユーザー**  
**email:admin@test.com**  
**password:admin1234**　　

**name:一般ユーザー**  
**email:generai1@gmail.com**  
**password:password**

## PHPUnitを利用したテスト
以下のコマンド
``` bash
//テスト用データベースの作成
docker-compose exec mysql bash
mysql -u root -p
//パスワードはrootと入力
create database test_database;

docker-compose exec php bash
php artisan migrate:fresh --env=testing
./vendor/bin/phpunit
```

## 使用技術(実行環境)
- PHP7.4.9
- Laravel8.83.27
- MySQL8.0.26

## ER図
<img width="453" alt="スクリーンショット 2025-03-20 15 29 26" src="https://github.com/user-attachments/assets/96aa110b-59fc-41d3-b905-4e5f9e6d4244" />


## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/
