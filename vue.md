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

## vue-router でページ遷移

```js

<template>
      省略
        :items='users'
        @click:item='clickItem'
      ></v-data-table>
      省略
</template>
<script>
export default{
  methods: {
    clickLink(item){
      this.$router.push({name:  'edit', params: {id: item.user_id}})
    }

  }
}
</script>
```

## Material Design Icon の適用手順

```terminal
$ npm install @mdi/js
```

```js

<template>
  <p>{{icons.mdiPlus}}</p>
</template>
<script>
import {
        mdiAccount,
        mdiPencil,
        mdiShareVariant,
        mdiDelete,
        mdiPlus,
        mdiClose,
    } from '@mdi/js'
export default {
    data: () => ({
        icons: {
            mdiAccount,
            mdiPencil,
            mdiShareVariant,
            mdiDelete,
            mdiPlus,
            mdiClose,
        },
</script>
```

## v-dialog

vuetify で modal 表示させる時に使う。

## v-for

data にある配列を iterate させるときなどに使う。

## 値をカンマ区切りにする

```js
var num = 700000000
var formatter = new Intl.NumberFormat("ja-JP")
formatter.format(num)
// 700,000,000
```

# watch, computed, method の違い

参考記事

https://qiita.com/yukibe/items/f49fcb935363200916fe

# Vue.set()

参考記事

https://qiita.com/tmak_tsukamoto/items/7623f458448fa7cd01c7

```js
data: () => {
  return {
    users: {},
  }
}
省略

this.users.user1 = { name: "Soma", age: 23 }
// 検知機構を持たないプロパティになる

Vue.set(this.users, "user1", { name: "Soma", age: 23 })
// 検知機構あり this.$set がエイリアス
```

# data を極力定義しない

参考記事

https://qiita.com/kahirokunn/items/b4f3ede5b2eb94711880#data%E3%82%92%E6%A5%B5%E5%8A%9B%E5%AE%9A%E7%BE%A9%E3%81%97%E3%81%AA%E3%81%84

data の値が定数などの変更のない値を定義している場合は、computed に移動させる。
computed は read only で、変更の心配がないのでバグを減らせる。

# axios get と post で日本語入りの params を送信した時の違い

参考記事

https://qiita.com/Sekky0905/items/dff3d0da059d6f5bfabf

axios というよりは、 HTTP 通信の使用の話だが、

axios.get の第二引数に入れる params 日本語の value が含まれている場合、バックエンドに渡される際に urlencode されたまま扱われることがあるので、注意する。

axios.post に変えれば解決した。

# @keydown

参考記事

https://stackoverflow.com/questions/33257379/how-to-fire-an-event-when-v-model-changes/33257642#33257642

```js
<input @keydown="foo" v-model >
// 文字を入力するたびにイベント発火する

<input @change="foo" v-model >
// enter を押すもしくはどこか別の場所をクリックするたびにイベント発火する
```

# @keypress

```js
<v-text-field @keypress.enter.native="goEvent()"></v-text-field>
// エンターを押すとイベント発火するように設定
```

# activated(), deactivated()

コンポーネントが活性化・非活性化する時に呼び出されるライフサイクルメソッド

# v-form で enter で submit するのを無効にする。

```js
<v-form @submit.prevent>
  <v-text-field
    v-model="name"
    @input="hogehoge"
  >
  </v-text-field>
</v-form>
```

# \$refs で親コンポーネントから子コンポーネントのメソッド呼び出し

FugaComponent.vue(親)

```js
<template>
  <HogeComponent
  ref="hoge"
  ></HogeComponent>
</template>
<script>
export default{
  methods:{
    fuga(){
      let params = {hoge: 'hoge', fuga: 'fuga'}
      this.$refs.hoge.hogehoge(params)
    }
  }
}
</script>
```

HogeComponent.vue(子)

```js
<script>
export default{
  methods:{
    hogehoge(params){
      console.log('Hello!!')
      console.log(params.hoge)
      console.log(params.fuga)
    }
  }
}
</script>
```

# Vuetify を blade で使う。

参考記事

https://laracasts.com/discuss/channels/laravel/how-to-add-vuetify-to-a-laravel-project

```js
<script>
  const vuetifyOptions={};
  const app = new Vue({
      vuetify: new Vuetify(vuetifyOptions),
      el: '#app',
  });
</script>
```

# Vuetify は 2020/12 時点で Vue3 はサポートしていない

https://stackoverflow.com/questions/63533675/how-do-i-register-vuetify-2-3-or-any-packages-in-a-vue-3-project


# v-expansion-panel

https://vuetifyjs.com/ja/components/expansion-panels/


# v-combobox

https://vuetifyjs.com/ja/components/combobox/

# v-data-table

https://vuetifyjs.com/en/api/v-data-table/

https://vuetifyjs.com/en/api/v-edit-dialog/


# v-tabs

https://vuetifyjs.com/ja/components/tabs/