# JSON.parse()

文字列を JSON として解析

```js
const json = '{"result":true, "count":42}'
const obj = JSON.parse(json)

console.log(obj.count)
// 42
console.log(obj.result)
// true
```

# npm run dev の副作用

```js
$ npm run dev
```

をすると、public/ の中身が一掃される。

なので、public/ に何か手を加える時は、npm run dev してからする必要がある。

# npm run hot での注意点

参考記事
https://vue-loader.vuejs.org/guide/hot-reload.html#state-preservation-rules

# slice() と splice() の違い

参考記事

https://sutefu7.hatenablog.com/entry/2019/08/25/113739

引数が少し違うのと、元の配列に影響を与えるか与えないかの違いがある。

```js
const array_1 = [10, 20, 30]
const array_2 = array_1.slice(1, 2) // 1 は インデックス番号、2 はインデックス番号ではなく普通の順番(つまり 20)
// array_1 = [10, 20, 30]
// array_2 = 20

const array_3 = array_1.splice(1, 2) // 1 はインデックス番号、2 はそこから何個分とるか
// array_1 = 10
// array_3 = [20, 30]
```

# Object.entries() と map でオブジェクトから配列へ

参考記事

https://qiita.com/kws9/items/6ce80ae1c0fd28a3a9d7

Object.entries() でオブジェクトを配列に変換できるが、同時に map も使うことによって、自由に配列を操作できる。

```js
Object.entries(obj).map(([key, value]) => ({ key, value }))
```

# スプレッド構文で配列の展開

参考記事

https://qiita.com/Nossa/items/e6f503cbb95c8e6967f8

```js
var array = [1, 2, 3]
var strings = ["a", "b", "c"]

array.push(...strings)

// ... で配列を展開できる
// array = [1, 2, 3, 'a','b','c']
```

# papaparse

参考記事

https://www.papaparse.com

# encoding-japanese

参考記事

https://github.com/polygonplanet/encoding.js/blob/master/README_ja.md

文字コードの変換

# file-saver

参考記事

https://www.npmjs.com/package/file-saver

# Math.ceil

数値の小数点以下切り捨て

https://www.javadrive.jp/javascript/math_class/index3.html#section2

```js
Math.ceil(3.2) = 3
Math.ceil(-3.4) = -3
```

# marked.js

マークダウン → html への変換時に使った。

https://www.wakuwakubank.com/posts/699-javascript-markedjs/

https://qiita.com/amay077/items/704d48130e5cf17e8654

# IE(InternetExplorer)11 環境での Javascript 非対応

https://qiita.com/siruku6/items/3465fd6e0588ee35cc78

ex. axios(Promise), for-of, ``(バッククウォート)


# FileReader を使った excel ファイルのアップロードとダウンロード

```javascript
readFile(e) {
    const reader = new FileReader();
    const file = e.target.files[0];
    reader.onload = e => {
      this.uploadFile(e.target.result);
    };
    reader.readAsDataURL(file);
  },
uploadFile(file) {
  const path = "http://localhost:5000/upload";
  const data = new FormData();
  data.append("file", file);
  const headers = { responseType: "blob", dataType: "binary" };
  axios.post(path, data, headers)
    .then(res => {
      const type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
      const fileURL = window.URL.createObjectURL(new Blob([res.data], { type: type }));
      const fileLink = document.createElement("a");
      const dateString = new Date().toLocaleDateString("ja-jp");
      fileLink.href = fileURL;
      fileLink.setAttribute("download", dateString + ".xlsx");
      fileLink.click();
    })
    .catch(err => {
      console.log("err");
      console.log(err);
    });
}
```

#  Error: `gyp` failed with exit code: 1

https://codenote.net/nodejs/npm/4233.html

# GAS

## Spreadsheet

### スプレッドシートのレイアウト

https://hirachin.com/post-4063/


# async await と forEach

https://qiita.com/frameair/items/e7645066075666a13063

https://qiita.com/jkr_2255/items/62b3ee3361315d55078a

https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/forEach#polyfill