new:
	@echo "Creating new challenge..."
	@read -p "Enter level(easy,medium,hard,super-hard): " level; \
	read -p "Enter challenge name: " challenge_name; \
	mkdir -p $$level/$$challenge_name; \
	touch $$level/$$challenge_name/code.ts; \
	touch $$level/$$challenge_name/question.md; \
	touch $$level/$$challenge_name/answer.md; \
	echo "Done!"; \