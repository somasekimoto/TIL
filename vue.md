## computed

作成中のアプリで、算出プロパティなるものを使った。

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

## data

React で言うところの、constructor
初期値をいれる。

```js

<textfield v-model="text"></textfield>
<p>{{text}}</p> //入力した文字と同じ文字が入力される

<button v-on:click="count++">You clicked me {{ count }} times.</button> //{{count}}の部分がクリックするたび増える

<input type="checkbox" id="checkbox" v-model="checked">
<label for="checkbox">{{ checked }}</label> //true false が変化する

<script>
export default {
  data () {
    return {
      text: '',
      count: 0,
      checkbox: false,
    }
  }
}

</script>
```

## mounted()

インスタンスがマウントされたちょうど後に呼ばれる function
React で言うところの componentDidMount() や useEffect

vue.js のライフサイクルの説明がわかりやすい記事

https://qiita.com/chan_kaku/items/7f3233053b0e209ef355

```js
<script>
export default {
  mounted(){
    this.hogehoge(function(){
      // ビュー全体がレンダリングされた後にのみ実行されるコード
    })
  }
}
</script>
```

beforeCreated, created, beforeMount など、マウントされるよりも前に実行される function もある。

## vue-router

参考記事

https://noumenon-th.net/programming/2020/03/21/laravel-vue-router/

### laravel x Vue.js の vue-router 手順

#### 1. npm install && npm install vue-router

#### 2. resources/views/index.blade.php (土台のビュー作成)

```html
<!doctype html>
〜省略〜
<head>
    〜省略〜
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div id="app">
        <router-view />    <= これを記述
    </div>
</body>

</html>
```

#### 3. routes/web.php (これは人によってはしなくても良い)

```php
Route::get('{any}', function () {
    return view('index');
})->where('any', '.*');
```

どの URL にアクセスしてもこの index.blade.php が土台とすることができる。

#### 4. resources/js/app.js

```js
require("./bootstrap")

window.Vue = require("vue")

import Vue from "vue"
import router from "./router" //追記

const app = new Vue({
  el: "#app",
  router: router, //追記
})
```

#### 5. resources/js/router.js

```js
import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)

import index from "./components/index.vue"

import edit from "./components/edit.vue"

const router = new VueRouter({
  mode: "history",
  routes: [
    {
      path: "/books",
      name: "index", //router-link というコンポーネントを使うときに必要らしい
      component: index,
    },
    {
      path: "/books/:id/edit",
      name: "edit",
      component: edit,
    },
  ],
})

export default router
```

#### 6. webpack.mix.js

```js
mix
  .js("resources/js/app.js", "public/js")
  .js("resources/js/router.js", "public/js") //追記
  .sass("resources/sass/app.scss", "public/css")
```

#### 7. 子コンポーネントたち作成

## axios で、デフォルトで CSRF トークンを設定(laravel x Vue.js)

### resources/js/bootstrap.js を編集

```js
window.axios.defaults.headers.common["X-CSRF-TOKEN"] = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content")
```

これで OK
