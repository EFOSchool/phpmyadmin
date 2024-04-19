// Import necessary testing libraries or utilities
import { expect } from 'chai';
import * as RelationFunctions from '../../resources/js/src/table/relation.ts';

describe('Foreign Key Drop Redirect and Reload Tests', () => {
    it('should not redirect and reload to the wrong page after dropping a foreign key', async () => {
        // Simulate dropping a foreign key
        const result = await RelationFunctions.drop_foreign_key_anchor();

        // Assuming handleDropForeignKey returns a result indicating success or failure
        expect(result.success).to.be.true;

        // Assuming there's a function to check the current URL or state after the action
        const currentUrl = getCurrentUrl();

        // Check that the current URL or state is as expected after dropping the foreign key
        expect(currentUrl).to.not.include('/index.php?route=/table/relation');

        // Add more assertions if needed to check other aspects of the behavior
    });
});

// File 
