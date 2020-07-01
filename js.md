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