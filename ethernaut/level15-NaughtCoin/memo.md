## 問題

- Naught CoinというERC20規格のトークンがあり、すべてのbalanceを自分（ウォレット）が持っている。


- transfer関数がoverrideされていて、そのmodifierによって、10年経たないとtransferが実行できないようになっている

- この状況で、自分のトークンバランスを0にする




##　参考

https://coinsbench.com/15-naught-coin-ethernaut-explained-afac57c4a9cd

## ハック

###　前提

- ERC20規格には、transfer以外に、transferFrom関数がある

-  approveさえしてれば、 transferFrom 関数を使って第三者によるtransferができる


### 手順

- ./Hack.solのように、自分のすべてのトークンバランスをそのコントラクトにtransferFromによって移すコントラクトを書きデプロイする。

- デプロイされたコントラクトアドレス引数に、コンソール画面から approveする

- コントラクトのhack関数をよび、transferFromが成功すればクリア