## 質問
これなぜkeyof T[P]はextends neverになることがあるんでしょうか？

```ts
type DeepReadonly<T> = {
    readonly[P in keyof T]: keyof T[P] extends never ? T[P] : DeepReadonly<T[P]>
}
```


## 回答


`keyof T[P] extends never`が`true`になる状況は、`T[P]`がプリミティブ型（例えば`number`, `string`, `boolean`）、`null`, `undefined`、または関数などのオブジェクト型で、その型に対する`keyof`が`never`に評価される場合です。

例を考えましょう：

1. `T[P]`が`number`の場合、`keyof number`は`never`です。
2. `T[P]`が関数（例えば`() => void`）の場合、`keyof (() => void)`も`never`です。
3. `T[P]`が`null`または`undefined`の場合、`keyof null`と`keyof undefined`も`never`です。

このような場合に`keyof T[P] extends never`が`true`になり、そのプロパティに対して深く読み取り専用の型を適用しないようにします（つまり、`T[P]`をそのまま使います）。

逆に、`T[P]`がオブジェクトや配列のような非プリミティブ型で、その型に対する`keyof`が`never`以外に評価される場合、再帰的に`DeepReadonly`を適用します。

このロジックによって、型`T`内のすべてのネストされたプロパティに対して読み取り専数用にする`DeepReadonly`型が構築されます。