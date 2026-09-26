local _npcName = nil
Game = {}
Game.__index = Game

function Game.createNpcType(name)
	_npcName = name
end
