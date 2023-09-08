



## 問題

- 


- 

- 




##　参考

https://dev.to/nvn/ethernaut-hacks-level-16-preservation-2ea7

## ハック

###　前提

- delegatecall() で他コントラクトのストレージを変更する際は、変数名とslotについて注意する必要がある

- 


### 手順

- delegatecallが呼び出されたことによって変更される変数について理解する

**setFirstTimestamp もsetSecondTimestampも、意図としては呼び出し元コントラクト(Preservation)のstoredTimeを変更するためのものだが、呼び出し先のコントラクト(LibraryContract)でstoredTimeがslot0に設定されているので、delegatecallがPreservationで使われた際には、Preservationのslot0である、timeZone1Libraryも変更されてしまう。**

なので、これを利用してハックする。

- timeZone1Library変数を変更する。

Hack.solのようなコントラクトを書く。
デプロイする。


- Hack.solのコントラクトアドレスをsetFirstTimestamp()の引数に渡して実行

これでPreservationコントラクトのowner変数が自分のウォレットアドレスになったはず


## 覚えておくこと

delegatecallによって、呼び出し元(storage)と呼び出し先(logic)のコントラクト**同じ名前の変数**と、**同じslot**の値が変更されうる。今回のケースだと。安易にupgradeableコントラクトを自前で書かないこと。

