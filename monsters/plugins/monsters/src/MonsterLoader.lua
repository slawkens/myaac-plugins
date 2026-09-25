COMBAT_PHYSICALDAMAGE = 0
COMBAT_ENERGYDAMAGE = 1
COMBAT_EARTHDAMAGE = 2
COMBAT_FIREDAMAGE = 3
COMBAT_UNDEFINEDDAMAGE = 4
COMBAT_LIFEDRAIN = 5
COMBAT_MANADRAIN = 6
COMBAT_HEALING = 7
COMBAT_DROWNDAMAGE = 8
COMBAT_ICEDAMAGE = 9
COMBAT_HOLYDAMAGE = 10
COMBAT_DEATHDAMAGE = 11
COMBAT_AGONYDAMAGE = 12
COMBAT_NEUTRALDAMAGE = 13

COMBAT_COUNT = 14

COMBAT_NONE = 255

Game = {}
Game.__index = Game

local monster_ = nil

function Game:createMonsterType(name)
   local monsterType = {}             	-- our new object
   setmetatable(monsterType,Game)  		-- make Game handle lookup
   monsterType.name = name      		-- initialize our object
   monsterType.register = function (self, monster)
   	monster_ = monster
   	monster_['testing'] = self.name
   end

   return monsterType
end

function getMonster()
	return monster_
end

function RegisterPrimalPackBeast(nothing)

end
