# GAS


## Google Workspace のアカウントパスワード変更

1. 特権管理者アカウントでスクリプトを開く

2. サービス "Admin SDK API" の追加

3. Gas のコード変更

```js
function doGet(e) {
  var address = e.parameter.address
  var newPassword = e.parameter.password
  var user = AdminDirectory.Users.get(address);
  var userKey = user.id
  let data = {
    "password": newPassword, // 仮パスワード
    "changePasswordAtNextLogin": false, // ログイン後にパスワードの変更を強制しない
  }
  AdminDirectory.Users.update(JSON.stringify(data),userKey);
}
```
