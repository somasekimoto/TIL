// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

interface IGatekeeperTwo {
    function enter (bytes8 _gateKey) external returns(bool);
}

contract GatePassTwo {
    bool private succeeded;
    constructor(address _gateAddr){
        IGatekeeperTwo keeper = IGatekeeperTwo(_gateAddr);
        bytes8 _gateKey = bytes8(uint64(bytes8(keccak256(abi.encodePacked(address(this))))) ^ (type(uint64).max));
        succeeded = keeper.enter(_gateKey);
    }

    function isSucceeded() public view returns(bool){
        return succeeded;
    }
}