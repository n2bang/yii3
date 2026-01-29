---
description: 'Help user init memory bank for the project'
agent: agent
---

Your Goal: Execute steps to init memory for the project, Help AI can understand project structure, technical stack, and workflow.

# Requirements for init memory bank
- Run the prompt /start-custom-instructions.prompt.md to set custom instructions for memory bank
- Read the document (https://docs.cline.bot/prompting/cline-memory-bank)
- Create a new folder .LunaSpecs/memory-bank
- Init memory bank
- Use #tool:search/codebase to understand project architecture
- Update systemPatterns.md, techContext.md, productContext.md based on your findings
- Ask user to review files and update them if needed