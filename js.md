# JSON.parse()
文字列を JSON として解析

```js
const json = '{"result":true, "count":42}';
const obj = JSON.parse(json);

console.log(obj.count);
// 42
console.log(obj.result);
// true
```

# npm run dev の副作用

```js
$ npm run dev
```

をすると、public/ の中身が一掃される。

なので、public/ に何か手を加える時は、npm run dev してからする必要がある。

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