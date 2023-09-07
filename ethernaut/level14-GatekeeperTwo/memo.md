
## 問題


GatekeeperOneと同じように、modifier gateOne,gateTwo,gateThreeのバリデーションを潜り抜けて、変数entrantをtrueにする

## 参考

https://blog.dixitaditya.com/ethernaut-level-14-gatekeeper-two

https://ardislu.dev/ethernaut/14


##　ハック

### 前提

- gateOne, gateTwo, gateThreeのmodier内のバリデーションを突破する必要がある

- extcodesizeというassemblyメソッドを知る

- Inline Assembly で使用されるcaller()メソッドについて知る。msg.senderと同じ

- solidityの論理演算子 " ^ " がXORであること

- A XOR B = C が成り立つとき、A XOR C = B も成り立つことを理解する


### 手順

#### gateOneを突破する

```sol
require(msg.sender != tx.origin);
```
- level13とかと同様、コントラクトアドレスから呼び出す必要ある。


#### gateTwoを突破する

```sol
uint x;
assembly { x := extcodesize(caller()) }
require(x == 0);
```

- caller()というのはmsg.senderと同義

- extcodesizeというのは、引数として渡されたアドレスがコードをどれくらい持っているかを判別。なのでコントラクトのアドレスだと1以上が、EOAだと0が返される。

- **ここで注意すべきなのは、コントラクトがまだデプロイされていなかったり、constructorの処理中だった場合は0を返す**


- **なので、ここでは gateOneとの兼ね合いを考えると、ハック用のコントラクト内のconstructor内でenter関数を読んだ方が良さそう**

#### gateThreeを突破する

```sol
require(uint64(bytes8(keccak256(abi.encodePacked(msg.sender)))) ^ uint64(_gateKey) == type(uint64).max);
```

- 一見難しいように見えるが、式自体はXORを使っているだけで、A XOR B = C となっていて、A XOR C = B が成り立つので、gateKeyが以下のようにできる。

```sol
bytes8 gateKey = bytes8(uint64(bytes8(keccak256(abi.encodePacked(msg.sender)))) ^ type(uint64).max);
```

完成したコードは ./Hack.sol



### ChatGPTへの質問

#### 質問

```sol
uint x;
assembly { x := extcodesize(caller()) }
require(x == 0);
```

のcaller()って何なのでしょうか？


####　回答

`caller()`という関数名が使われていることが混乱を生んでいるようですが、このコードの文脈での`caller()`は、Inline Assemblyで使用されるEthereum Virtual Machine (EVM)の命令`CALLER`を指します。この命令は`msg.sender`と同じアドレスを返します。

`extcodesize(caller())`はInline Assemblyを使用して、呼び出し元のコントラクトアドレス（すなわち`msg.sender`）のコードサイズを取得します。

- もしこのサイズが0であれば、呼び出し元は外部所有アカウント（Externally Owned Account, EOA）であり、スマートコントラクトではありません。
- サイズが0より大きい場合、呼び出し元はスマートコントラクトです。

この`require(x == 0);`命令は、呼び出し元がEOAであることを確認しています。もし呼び出し元がスマートコントラクトであれば、この`require`ステートメントは失敗し、トランザクションはリバートします。

このようなチェックは、コントラクトがEOAからしか呼び出せないように制限したい場合などに有用です。ただし、そのような制限が必要かどうかは設計の問題であり、全てのアプリケーションでこのような制限が必要なわけではありません。