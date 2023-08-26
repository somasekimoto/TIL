# 問題

GatekeeperOneコントラクトの変数entrantにアドレスを設定する

- level4-telephoneとlevel5-tokenが参考になるらしい
- gasleft使う必要がある




## ハック

### 前提

- entrantにtx.originを設定する関数のmodifierが3つあって、それぞれのバリデーション通る必要がある


### 手順
- modifier gateOneを通る
msg.sender != tx.originである必要があるので、コントラクトからの呼び出しが必要

```sol

```

- modifier gateTwoを通る
- ガスの残り(gasleft)が、8191の倍数である必要がある


- modifier gateThree を通る



### ChatGPTへの質問

**最後のuint160になる理由がわからないです**

```solidity
require(uint32(uint64(_gateKey)) == uint16(uint160(tx.origin)), "GatekeeperOne: invalid gateThree part three");
```

*Ethereumのアドレスは、基本的には20バイト（160ビット）の長さを持っています。
Solidityでは、これらのアドレスを通常address型で扱いますが、数値操作を行いたい場面ではuint160にキャストして操作することが一般的です。
このmodifierの中でtx.origin（20バイトのアドレス）がuint160にキャストされているのは、その後でビットレベルでの操作（この場合は更にuint16にキャストする操作）を行いたいからです。
すなわち、uint160(tx.origin)とすることで、20バイト（160ビット）のアドレスを160ビットの整数に変換しています。この整数を更にuint16にキャストすることで、下位16ビットだけを取り出しています。このようにして、tx.originの下位16ビットと_gateKeyの下位32ビットを比較しています。そのために、tx.originをuint160にキャストしています。*

- Ethereumのアドレスは20bytes=160bitの長さ

**32bitと16bitの値を比較するときって、そもそもbit数が違うから常にfalseなんじゃないでしょうか**


*ビット数が異なる整数を比較する際には、通常、比較のために整数同士が同じビット数に拡張されます。言い換えれば、短いビット長の整数は比較のためにより長いビット長の整数に暗黙的に変換されることが多いです。例えば、uint16の0x1234（16ビット）とuint32の0x00001234（32ビット）があるとき、これらの値は等しいと見なされます。なぜなら、uint16の値がuint32に暗黙的に変換され、上位ビットに0が補われるためです。Solidityにおいても、このような型変換が暗黙的に行われます。したがって、uint32とuint16の値を比較する際には、uint16の値が暗黙的にuint32に変換され、その上で比較が行われます。このコントラクトでは、uint32(uint64(_gateKey))とuint16(uint64(_gateKey))を比較していますが、uint16(uint64(_gateKey))はuint32に変換されてから比較されるでしょう。この比較が真になる条件は、_gateKeyの下位16ビットと、その次の16ビット（ビット17-32）が同じである場合です。*

- ビット数が異なる整数を比較するときは、暗黙的に短い方の数字を長い方に合わせる
    - uint16 0x1234と uint32 0x00001234のとき2つは等しくなる


