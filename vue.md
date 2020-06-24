# computed

作成中のアプリで、ログイン ID とメールアドレスの@以前が完全一致するので、、
算出プロパティなるものを使った。

```html
<v-col cols="12" sm="6" md="3">
  <v-text-field
    label="mail_address"
    placeholder="your.login.id@company.co.jp"
    v-model="mail_address"
    disabled
  ></v-text-field>
</v-col>
```

```js
export default {
    computed: {
        mail_address: function () {
            return this.login_id + '@company.co.jp'
        }
    },

    〜省略〜
```

これで、login_id を入力すれば、自動でメールアドレスも入力される。

また、

```js
export default {
    methods: {
        changeAddress: function () {
            return this.login_id + '@company.co.jp'
        }
    },

    〜省略〜
```

上記のように、method にしても結果は同じものが得られる。

**しかし、算出プロパティはリアクティブな依存関係にもとづきキャッシュされる。**
