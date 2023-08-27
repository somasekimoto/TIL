

## 質問


```ts
type MyReadonly2<T, K extends keyof T = keyof T> = {
  readonly [P in K]: T[P];
} & {
  [P in keyof T as P extends K ? never : P]: T[P]
};

```

最初の方の、
= keyof T 
が何を表してるのか
と、
&の意味が何なのか
を教えてください


## 回答

### `= keyof T`

この部分はTypeScriptでのデフォルト型引数を設定しています。つまり、`K extends keyof T = keyof T`という形で型引数`K`にデフォルト値として`keyof T`を設定しています。このデフォルト値は、型引数`K`が指定されなかった場合に使用されます。このようにすることで、`K`が指定されない場合には`T`のすべてのプロパティが読み取り専用になります（`keyof T`は`T`のすべてのプロパティのキーを取得します）。

### `&`

`&`はTypeScriptの交差型（Intersection Types）を表しています。交差型は2つ以上の型を一つに結合するものです。例えば、`A & B`は型`A`と型`B`の両方の特性を持つ型になります。

```typescript
type A = {
  a: number;
};

type B = {
  b: string;
};

type C = A & B; // { a: number; b: string; }
```

この場合、`MyReadonly2<T, K>`は二つのマッピング型を合成しています：

1. `{ readonly [P in K]: T[P]; }` は`K`で指定されたプロパティを読み取り専用にします。
2. `{ [P in keyof T as P extends K ? never : P]: T[P] }` は`K`で指定されなかったプロパティをそのまま維持します。

`&`を用いてこれらを合成することで、`K`で指定されたプロパティだけを読み取り専用にし、それ以外のプロパティはそのままにする新しい型が生成されます。