```ts
type Last<T extends any[]> = T extends [...any[], infer U] ? U : never;
```


## 質問

indexが取りたい時ってどうします？最後の値を取得したんですよね

## 回答

配列のインデックスだけを取得したい場合は、以下のように`number`型で取得できます。

```typescript
type ArrayKeys = Exclude<keyof any[], keyof []>;

type IndicesOfArray<A> = Extract<keyof A, ArrayKeys>;
```

ここで、`keyof any[]` は配列の全てのプロパティを含む一方で、`keyof []` は`Array.prototype`に由来するプロパティを取得します。これらを`Exclude`と`Extract`を使って組み合わせることで、`IndicesOfArray<A>`は配列`A`のインデックスのみを含む型となります。

例えば：

```typescript
type MyArray = [string, number, boolean];
type Indices = IndicesOfArray<MyArray>; // "0" | "1" | "2"
```

最後の値（要素）を取得する場面に関しては、TypeScriptでは直接的な方法で最後の要素の型を取得する方法は提供していないのですが、以下のような型を定義することで、最後の要素の型を取得することができます。

```typescript
type LastElement<T extends any[]> = T extends [...any[], infer U] ? U : never;

type MyArray = [string, number, boolean];
type Last = LastElement<MyArray>; // boolean
```

この`LastElement<T>`型は配列`T`の最後の要素の型を取得します。`infer U`で最後の要素の型を`U`として推論しています。