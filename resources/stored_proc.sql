DELIMITER $$
CREATE  PROCEDURE `spFixMemberEwalletLEdger`(IN `recIsByMember` INT, IN `recEntryID` BIGINT(20))
BEGIN

 DECLARE intEntryID BIGINT DEFAULT 0;
 DECLARE intMemberID BIGINT DEFAULT 0;

 DECLARE intLedgerID BIGINT DEFAULT 0;
 DECLARE dtDateTimeEarned DATETIME DEFAULT NULL;
 DECLARE dblINAmount DOUBLE(13,3) DEFAULT 0;
 DECLARE dblOUTAmount DOUBLE(13,3) DEFAULT 0;
 
 DECLARE dblOldBal DOUBLE(13,3) DEFAULT 0;
 DECLARE dblNewBal DOUBLE(13,3) DEFAULT 0;
 
 DECLARE cursorMemberEntryCount BIGINT DEFAULT 0;
 DECLARE cursorMemberEntryCntr BIGINT DEFAULT 0;
		 
 DECLARE cursorMemberLedgerCount BIGINT DEFAULT 0;
 DECLARE cursorMemberLedgerCntr BIGINT DEFAULT 0;
 
 DEClARE cursorMemberEntry CURSOR FOR
 SELECT 
 	mentry.`EntryID`,
 	mentry.`MemberID`
 FROM `memberentry` mentry
 WHERE if(recIsByMember = 1, mentry.EntryID, 0) = if(recIsByMember = 1, recEntryID, 0)
 ORDER BY mentry.`EntryID` ASC;

 DEClARE cursorMemberLedger CURSOR FOR
 SELECT 
        eledger.LedgerID,
        eledger.DateTimeEarned,
        eledger.INAmount,
        eledger.OUTAmount
 FROM ewalletledger as eledger
 WHERE eledger.MemberID = intMemberID
 ORDER BY eledger.LedgerID ASC,
 	eledger.DateTimeEarned ASC;
 
 
  OPEN cursorMemberEntry;
 SELECT FOUND_ROWS() INTO cursorMemberEntryCount; 
 if(cursorMemberEntryCount > 0) then 
 	SET cursorMemberEntryCntr = 0;
 	LoopMemberEntry:WHILE cursorMemberEntryCntr < cursorMemberEntryCount DO 

       SET intEntryID = 0;
       SET intMemberID = 0;
 	   FETCH cursorMemberEntry
 	   INTO 
 		intEntryID,
 		intMemberID;
 
         if(intMemberID > 0) then 
         	 
         	SET dblOldBal = 0;
            SET dblNewBal = 0;
            
                          OPEN cursorMemberLedger;
             SELECT FOUND_ROWS() INTO cursorMemberLedgerCount; 
             if(cursorMemberLedgerCount > 0) then 
                SET cursorMemberLedgerCntr = 0;
                LoopMemberLedger:WHILE cursorMemberLedgerCntr < cursorMemberLedgerCount DO 

                   SET intLedgerID = 0;
                   SET dtDateTimeEarned = NULL;
                   SET dblINAmount = 0;
                   SET dblOUTAmount = 0;
                   FETCH cursorMemberLedger
                   INTO 
                    intLedgerID,
                    dtDateTimeEarned,
                    dblINAmount,
                    dblOUTAmount;

                    SET dblOldBal = dblNewBal;
                    
                    if(dblINAmount > 0) then
                      SET dblNewBal = dblNewBal + dblINAmount;
                      UPDATE ewalletledger SET 
                      	OldBalance = dblOldBal,
                        RunningBalance = dblNewBal
                      WHERE LedgerID = intLedgerID;
                    end if;

                    if(dblOUTAmount > 0) then
                      SET dblNewBal = dblNewBal - dblOUTAmount;
                      UPDATE ewalletledger SET 
                      	OldBalance = dblOldBal,
                        RunningBalance = dblNewBal
                      WHERE LedgerID = intLedgerID;
                    end if;
             
                    SET cursorMemberLedgerCntr = cursorMemberLedgerCntr + 1;
             
                END WHILE;              
             end if;
             
             CLOSE cursorMemberLedger;
             
         end if;
 
 	    SET cursorMemberEntryCntr = cursorMemberEntryCntr + 1;
 
 	END WHILE;  
 end if;
 
 CLOSE cursorMemberEntry; 
 
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spDistributeEntryShare`(IN `recEntryID` BIGINT(20), IN `recPackageID` INT, IN `recCurrentDateTime` DATETIME)
BEGIN

	DECLARE COMPLAN_ID BIGINT(13) DEFAULT 1;
	DECLARE curDateTime DATETIME DEFAULT NOW();
    
  	DECLARE intTotalEntryShare INT(9) DEFAULT 0;
  	DECLARE dblEntryShareAmount DOUBLE(13,3) DEFAULT 0;
  	DECLARE dblAmountperShare DOUBLE(13,3) DEFAULT 0;
    DECLARE intEntryMemberID BIGINT DEFAULT 0;
	DECLARE strEntryCode VARCHAR(50) DEFAULT 0;
	DECLARE strPackage VARCHAR(50) DEFAULT 0;
    DECLARE intEntryID BIGINT DEFAULT 0;
    DECLARE intMemberID BIGINT DEFAULT 0;
    DECLARE intNoOfEntryShare BIGINT DEFAULT 0;
  	DECLARE dblMemberTotalEntryShare DOUBLE(13,3) DEFAULT 0;
    DECLARE dblEWalletRunningBal DOUBLE(13,3) DEFAULT 0;
    DECLARE dblAccuRewards DOUBLE(13,3) DEFAULT 0;
  	DECLARE dblMaxShareEntry DOUBLE(13,3) DEFAULT 0;
    DECLARE dblMemberEntryShareAmount DOUBLE(13,3) DEFAULT 0;
   
  	DECLARE cursorEntryPoolCount BIGINT DEFAULT 0;
  	DECLARE cursorEntryPoolCntr BIGINT DEFAULT 0;
    
    DEClARE cursorEntryPool CURSOR FOR
    SELECT 
    	mentry.`EntryID`,
    	mentry.`MemberID`,
		pckgentry.`NoOfEntryShare`,
        mentry.`TotalEntryShare` as MemberTotalEntryShare,
		COALESCE((SELECT COALESCE(RunningBalance,0) as Balance
        	FROM ewalletledger
            WHERE MemberID = mentry.`MemberID`
            ORDER BY LedgerID DESC,
            DateTimeEarned DESC
            LIMIT 1 
            )
        ,0) as MemberEWalletBalance,
		COALESCE(mentry.AccumulatedRewards,0) as AccumulatedRewards,
        pckgentry.MaxShareAmount
    FROM `memberentry` mentry
    INNER JOIN `package` pckg ON (mentry.`PackageID` = pckg.`PackageID`)
    INNER JOIN `packageentry` pckgentry ON (pckg.`PackageID` = pckgentry.`PackageID`)
    WHERE mentry.`IsEntryShareComplete` = 0
    AND mentry.EntryID != recEntryID
    ORDER BY mentry.`EntryDateTime` ASC;
        SET intEntryMemberID = 0;
    SET strEntryCode = '';
    SET strPackage = '';
    SELECT 
    	mentry.`MemberID`,
        mentry.EntryCode,
        pckg.Package
    FROM `memberentry` mentry
    INNER JOIN `package` pckg ON (mentry.`PackageID` = pckg.`PackageID`)
    WHERE mentry.`EntryID` = recEntryID
    LIMIT 1
    INTO 
    	intEntryMemberID,
        strEntryCode,
        strPackage;
            	
        SET dblEntryShareAmount = 0;
    SELECT 
    	pckgentry.`EntryShareAmount`
    FROM `packageentry` pckgentry
    WHERE pckgentry.`PackageID` = recPackageID
    LIMIT 1
    INTO 
    	dblEntryShareAmount;
    
        SET intTotalEntryShare = 0;
    SELECT SUM(pckgentry.`NoOfEntryShare`) as TotalEntryShare
    FROM `memberentry` mentry
    INNER JOIN `package` pckg ON (mentry.`PackageID` = pckg.`PackageID`)
    INNER JOIN `packageentry` pckgentry ON (pckg.`PackageID` = pckgentry.`PackageID`)
    WHERE mentry.`IsEntryShareComplete` = 0
    AND mentry.`EntryID` != recEntryID
    LIMIT 1
    INTO 
    	intTotalEntryShare;
	    SET dblAmountperShare = dblEntryShareAmount/intTotalEntryShare;
	if(intTotalEntryShare > 0 && dblAmountperShare > 0) then
    		
      	      	OPEN cursorEntryPool;
      	SELECT FOUND_ROWS() INTO cursorEntryPoolCount;    	
		if(cursorEntryPoolCount > 0) then      
			SET cursorEntryPoolCntr = 0;
    		LoopEntryPool:WHILE cursorEntryPoolCntr < cursorEntryPoolCount DO        
                SET intEntryID = 0;
                SET intMemberID = 0;
                SET intNoOfEntryShare = 0;
                SET dblMemberTotalEntryShare = 0;
                SET dblEWalletRunningBal = 0;
                SET dblAccuRewards = 0;
                SET dblMaxShareEntry = 0;
				FETCH cursorEntryPool
            	INTO 
                	intEntryID,
                	intMemberID,
                    intNoOfEntryShare,
                	dblMemberTotalEntryShare,
                    dblEWalletRunningBal,
                    dblAccuRewards,
                    dblMaxShareEntry;
				
                SET dblMemberEntryShareAmount = intNoOfEntryShare * dblAmountperShare;
                if((dblMemberTotalEntryShare + dblMemberEntryShareAmount) > dblMaxShareEntry) then
                	SET dblMemberEntryShareAmount = dblMaxShareEntry - dblMemberTotalEntryShare;
                end if;
                
    			if(dblMemberEntryShareAmount > 0 AND intEntryID != recEntryID) then
					
                    INSERT INTO ewalletledger SET
                        ComplanID = COMPLAN_ID,
                        MemberID = intMemberID,
                        EarnedFromMemberID = intEntryMemberID,
                        LevelNo = 0,
                        DateTimeEarned = recCurrentDateTime,
                        EarnedMonth = MONTH(recCurrentDateTime),
                        EarnedYear = YEAR(recCurrentDateTime),
                        INAmount = dblMemberEntryShareAmount,
                        OUTAmount = 0,
                        OldBalance = dblEWalletRunningBal,
                        RunningBalance = (dblEWalletRunningBal + dblMemberEntryShareAmount),
                        Remarks = CONCAT('Entry Share from ', strPackage ,' Entry Code : ',strEntryCode),
                        `Status` = 'Approved',
                        TransactionRefID = recEntryID,
                        DateTimeCreated = curDateTime,
                        DateTimeUpdated = curDateTime;
                	
                                        UPDATE `memberentry` SET
                    	TotalEntryShare = TotalEntryShare + dblMemberEntryShareAmount,
                        IsEntryShareComplete = if(TotalEntryShare >= dblMaxShareEntry,1,0),
                        AccumulatedRewards = (dblAccuRewards + dblMemberEntryShareAmount)
                    WHERE EntryID = intEntryID;
                
                end if;
				SET cursorEntryPoolCntr = cursorEntryPoolCntr + 1;
            
            END WHILE;         
        end if;
        
    end if; 
 
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGenerateRebates`(IN `recCurrentDateTime` DATETIME)
BEGIN
    DECLARE curDateTime DATETIME DEFAULT NOW();
    DECLARE REBATES_COMPLAN_ID BIGINT(13) DEFAULT 4;
    DECLARE intCompanyEntryID BIGINT DEFAULT 1;
    
    DECLARE intCutOffMonth BIGINT DEFAULT 1;
    DECLARE intCutOffYear BIGINT DEFAULT 1;
    
    DECLARE intcoachmicjavCutOffID BIGINT DEFAULT 0;
    DECLARE intCutOffID BIGINT DEFAULT 0;
    DECLARE intMainMemberEntryID BIGINT DEFAULT 0;
    DECLARE intMainMemberID BIGINT DEFAULT 0;
    DECLARE intMemberEntryID BIGINT DEFAULT 0;
    DECLARE intMemberID BIGINT DEFAULT 0;
    DECLARE intPackageID BIGINT DEFAULT 0;
    DECLARE intSponsorEntryID BIGINT DEFAULT 0;
    DECLARE intSponsorMemberID BIGINT DEFAULT 0;
    DECLARE intNewSponsorEntryID BIGINT DEFAULT 0;
    DECLARE intAcquiredByEntryID BIGINT DEFAULT 0;    
    DECLARE dblTotalPurchases DOUBLE(13,3) DEFAULT 0;
    DECLARE dblTotalRebatableValue DOUBLE(13,3) DEFAULT 0;
	DECLARE dblRebatesMaintainingBal DOUBLE(13,3) DEFAULT 0;    
	DECLARE dblTotalAcquiredRebatableValue DOUBLE(13,3) DEFAULT 0;    
    DECLARE dblEWalletRunningBal DOUBLE(13,3) DEFAULT 0;

    DECLARE dblPersonalRebatesPercent DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebateLevel1Percent DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebateLevel2Percent DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebateLevel3Percent DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebateLevel4Percent DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebateLevel5Percent DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebateLevel6Percent DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebateLevel7Percent DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebateLevel8Percent DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebateLevel9Percent DOUBLE(13,3) DEFAULT 0;
	
 	DECLARE dblTotalAPP DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblTotalAGP DOUBLE(13,3) DEFAULT 0;
    
 	DECLARE strRankLevel1 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel1Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel1APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel1AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel2 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel2Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel2APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel2AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel3 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel3Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel3APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel3AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel4 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel4Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel4APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel4AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel5 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel5Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel5APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel5AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel6 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel6Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel6APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel6AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel7 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel7Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel7APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel7AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel8 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel8Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel8APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel8AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel9 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel9Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel9APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel9AGPRV DOUBLE(13,3) DEFAULT 0;
       
    DECLARE intCutOffExist INT DEFAULT 0;
    
    DECLARE intLevelNo INT DEFAULT 0;
    DECLARE intLevelMax INT DEFAULT 9;
    DECLARE intLevelLimit INT DEFAULT 5;
	DECLARE dblRebatePercent DOUBLE(13,3) DEFAULT 0;
	DECLARE strRebatesType VARCHAR(250) DEFAULT '';
	DECLARE dblRebates DOUBLE(13,3) DEFAULT 0;

  	DECLARE cursorSponsorshipRebatesCount BIGINT DEFAULT 0;
  	DECLARE cursorSponsorshipRebatesCntr BIGINT DEFAULT 0;
    DEClARE cursorSponsorshipRebates CURSOR FOR
    SELECT 
        cutoff.`CutOffID`,
        cutoff.`MemberEntryID`,
        mentry.`MemberID`,
        mentry.`SponsorEntryID`,
        sprentry.`MemberID` as SponsorMemberID,
        cutoff.AcquiredByEntryID,
        cutoff.`TotalRebatableValue`,
        cutoff.`TotalAcquiredRebatableValue`,
        cutoff.`MaintainingBalance`
    FROM `memberentrycutoff` as cutoff
    INNER JOIN `memberentry` as mentry ON (mentry.`EntryID` = cutoff.`MemberEntryID`)
    INNER JOIN `memberentry` as sprentry ON (sprentry.`EntryID` = mentry.`SponsorEntryID`)
    WHERE mentry.`SponsorEntryID` IN (SELECT MemberEntryID FROM tblTempCollection WHERE LevelNo = intLevelNo)
    AND MONTH(cutoff.EndDate) = intCutOffMonth
    AND YEAR(cutoff.EndDate) = intCutOffYear;
    
    	SET intCutOffMonth = MONTH(recCurrentDateTime) - 1;
    if(intCutOffMonth = 0) then
    	SET intCutOffMonth = 12;
    end if;

	SET intCutOffYear = YEAR(recCurrentDateTime);
    if(MONTH(recCurrentDateTime) = 1) then
    	SET intCutOffYear = intCutOffYear - 1;
    end if;

    	UPDATE memberentrycutoff SET
    	AcquiredByEntryID = NULL,
        IsRebatesGenerated = 0,
        MaintainingBalance = COALESCE((SELECT packagerebates.`RebatesMaintainingBal`
        					  FROM `memberentry` 
                              INNER JOIN `packagerebates` ON (memberentry.`PackageID` = packagerebates.PackageID)
                              WHERE memberentry.EntryID = memberentrycutoff.MemberEntryID
                              LIMIT 1),0)
	WHERE MONTH(EndDate) = intCutOffMonth
    AND YEAR(EndDate) = intCutOffYear;
    
        UPDATE memberentrycutoff 
    SET
    	AcquiredByEntryID = MemberEntryID
    WHERE `TotalRebatableValue` >= `MaintainingBalance`
	AND MONTH(EndDate) = intCutOffMonth
    AND YEAR(EndDate) = intCutOffYear;

        UPDATE memberentrycutoff 
    SET
    	AcquiredByEntryID = NULL
    WHERE `TotalRebatableValue` < `MaintainingBalance`
	AND MONTH(EndDate) = intCutOffMonth
    AND YEAR(EndDate) = intCutOffYear;

        DROP TEMPORARY TABLE IF EXISTS tblTempCollection;
    CREATE TEMPORARY TABLE tblTempCollection
    SELECT 
    	CutOffID
    FROM `memberentrycutoff`
    LIMIT 0; 
    
        DELETE FROM tblTempCollection;
       
    SET intcoachmicjavCutOffID = 1;     GetUnAcquired:WHILE intcoachmicjavCutOffID > 0 DO
		
                SET intCutOffID = 0;
        SET intMemberEntryID = 0;
        SET intNewSponsorEntryID = 0;
        SET dblTotalPurchases = 0;
        SET dblTotalRebatableValue = 0;
        SET dblRebatesMaintainingBal = 0;
        SELECT 
            cutoff.`CutOffID`,
            cutoff.`MemberEntryID`,
            mentry.`SponsorEntryID`,
            cutoff.`TotalPurchases`,
            cutoff.`TotalRebatableValue`,
            cutoff.`MaintainingBalance`
        FROM `memberentrycutoff` as cutoff
        INNER JOIN `memberentry` as mentry ON (mentry.`EntryID` = cutoff.`MemberEntryID`)
        WHERE cutoff.`AcquiredByEntryID` IS NULL
        AND MONTH(EndDate) = intCutOffMonth
        AND YEAR(EndDate) = intCutOffYear
        ORDER BY cutoff.`CutOffID` DESC
        LIMIT 1
        INTO 
            intCutOffID,
            intMemberEntryID,
            intNewSponsorEntryID,
            dblTotalPurchases,
            dblTotalRebatableValue,
            dblRebatesMaintainingBal;
            
        SET intcoachmicjavCutOffID = intCutOffID;
      
      	if(intCutOffID > 0) then
                      
                    INSERT INTO tblTempCollection SET 
              CutOffID = intCutOffID;
    	  
          if(intNewSponsorEntryID = intCompanyEntryID) then
              UPDATE `memberentrycutoff` SET
                  AcquiredByEntryID = intCompanyEntryID
              WHERE CutOffID IN (SELECT CutOffID FROM tblTempCollection);
                                
                            DELETE FROM tblTempCollection;
          else
                            GetSponsor:WHILE intNewSponsorEntryID <> intCompanyEntryID DO
              
                                    SET intCutOffID = 0;
                  SET intMemberEntryID = 0;
                  SET intSponsorEntryID = 0;
                  SET intAcquiredByEntryID = 0;
                  SET dblTotalPurchases = 0;
                  SET dblTotalRebatableValue = 0;
                  SET dblRebatesMaintainingBal = 0;
                  SELECT 
                      cutoff.`CutOffID`,
                      cutoff.`MemberEntryID`,
                      mentry.`SponsorEntryID`,
                      COALESCE(cutoff.`AcquiredByEntryID`,0) as AcquiredByEntryID,
                      cutoff.`TotalPurchases`,
                      cutoff.`TotalRebatableValue`,
                      cutoff.`MaintainingBalance`
                  FROM `memberentrycutoff` as cutoff
                  INNER JOIN `memberentry` as mentry ON (mentry.`EntryID` = cutoff.`MemberEntryID`)
                  WHERE cutoff.`MemberEntryID` = intNewSponsorEntryID
                  AND MONTH(EndDate) = intCutOffMonth
                  AND YEAR(EndDate) = intCutOffYear
                  LIMIT 1
                  INTO 
                      intCutOffID,
                      intMemberEntryID,
                      intSponsorEntryID,
                      intAcquiredByEntryID,
                      dblTotalPurchases,
                      dblTotalRebatableValue,
                      dblRebatesMaintainingBal;
                      
                                    INSERT INTO tblTempCollection SET 
                      CutOffID = intCutOffID;

                  if(dblTotalRebatableValue >= dblRebatesMaintainingBal) then
                      UPDATE `memberentrycutoff` SET
                          AcquiredByEntryID = intMemberEntryID
                      WHERE CutOffID IN (SELECT CutOffID FROM tblTempCollection);
                                        
                                            DELETE FROM tblTempCollection;
                      
                      LEAVE GetSponsor;

                  elseif(intAcquiredByEntryID > 0) then
                      UPDATE `memberentrycutoff` SET
                          AcquiredByEntryID = intAcquiredByEntryID
                      WHERE CutOffID IN (SELECT CutOffID FROM tblTempCollection);
                                        
                                            DELETE FROM tblTempCollection;
                      
                      LEAVE GetSponsor;

				  elseif(intSponsorEntryID = intCompanyEntryID || intMemberEntryID = intSponsorEntryID) then
                      UPDATE `memberentrycutoff` SET
                          AcquiredByEntryID = intCompanyEntryID
                      WHERE CutOffID IN (SELECT CutOffID FROM tblTempCollection);
                                        
                                            DELETE FROM tblTempCollection;
                      
                      LEAVE GetSponsor;

                  else
                  		SET intCutOffExist = 0;
                        SELECT COUNT(*)
                        FROM tblTempCollection
                        WHERE CutOffID = intCutOffID
                        INTO 
                        	intCutOffExist;
                        
                        if(intCutOffExist > 1) then
                            UPDATE `memberentrycutoff` SET
                                AcquiredByEntryID = intCompanyEntryID
                            WHERE CutOffID IN (SELECT CutOffID FROM tblTempCollection);
                                              
                                                        DELETE FROM tblTempCollection;
                            
                        end if;
                        
                  end if;
              	
                  SET intNewSponsorEntryID = intSponsorEntryID;
                  
              END WHILE;           end if;
      end if;
      
    END WHILE;     
        DROP TEMPORARY TABLE IF EXISTS tblTempCutOff;
    CREATE TEMPORARY TABLE tblTempCutOff
    SELECT 
    	AcquiredByEntryID,
        SUM(TotalRebatableValue) as TotalAcquiredRebatableValue
    FROM `memberentrycutoff`
    WHERE MONTH(EndDate) = intCutOffMonth
	AND YEAR(EndDate) = intCutOffYear
    GROUP BY AcquiredByEntryID;
   
	    UPDATE memberentrycutoff SET
    	TotalAcquiredRebatableValue = COALESCE((SELECT SUM(tblTempCutOff.TotalAcquiredRebatableValue)
                                    FROM tblTempCutOff
                                    WHERE tblTempCutOff.AcquiredByEntryID = memberentrycutoff.MemberEntryID)
                            ,0)
    WHERE MONTH(EndDate) = intCutOffMonth
    AND YEAR(EndDate) = intCutOffYear;
	
	    SET intcoachmicjavCutOffID = 1;
    GetIssueRebates:WHILE intcoachmicjavCutOffID > 0 DO
		
    	        SET intLevelNo = 0;
		
                SET intCutOffID = 0;
        SET intMainMemberEntryID = 0;
        SET intMainMemberID = 0;
        SET intPackageID = 0;
        SET intSponsorEntryID = 0;
        SET intSponsorMemberID = 0;
        SET dblTotalPurchases = 0;
        SET dblTotalRebatableValue = 0;
        SET dblTotalAcquiredRebatableValue = 0;
        SET dblTotalAPP = 0;
        SET dblTotalAGP = 0;
        SET dblRebatesMaintainingBal = 0;
        SET dblEWalletRunningBal = 0;
        SELECT 
            cutoff.`CutOffID`,
            cutoff.`MemberEntryID`,
            mentry.MemberID,
            mentry.PackageID,
            mentry.`SponsorEntryID`,
            sprentry.MemberID as SponsorMemberID,
            cutoff.`TotalPurchases`,
            cutoff.`TotalRebatableValue`,
            cutoff.`TotalAcquiredRebatableValue`,
			COALESCE((SELECT
                        PersonalRunningBalance
                    FROM memberentryorder
                    WHERE HeadEntryID = cutoff.`MemberEntryID`
                    ORDER BY AccumulatedOrderID DESC
                    LIMIT 1),0) as PersonalRunningBalance,
			COALESCE((SELECT
                        GroupRunningBalance
                    FROM memberentryorder
                    WHERE HeadEntryID = cutoff.`MemberEntryID`
                    ORDER BY AccumulatedOrderID DESC
                    LIMIT 1),0) as PersonalRunningBalance,
            cutoff.`MaintainingBalance`,
            COALESCE((SELECT COALESCE(RunningBalance,0) as Balance
                FROM ewalletledger
                WHERE MemberID = mentry.`MemberID`
                ORDER BY LedgerID DESC,
                DateTimeEarned DESC
                LIMIT 1 
                )
            ,0) as MemberEWalletBalance
        FROM `memberentrycutoff` as cutoff
        INNER JOIN `memberentry` as mentry ON (mentry.`EntryID` = cutoff.`MemberEntryID`)
        INNER JOIN `memberentry` as sprentry ON (sprentry.`EntryID` = mentry.`SponsorEntryID`)
        WHERE cutoff.TotalRebatableValue >= cutoff.MaintainingBalance
        AND cutoff.IsRebatesGenerated = 0
        AND MONTH(cutoff.EndDate) = intCutOffMonth
        AND YEAR(cutoff.EndDate) = intCutOffYear
        ORDER BY cutoff.`CutOffID` DESC
        LIMIT 1
        INTO 
            intCutOffID,
            intMainMemberEntryID,
            intMainMemberID,
            intPackageID,
            intSponsorEntryID,
            intSponsorMemberID,
            dblTotalPurchases,
            dblTotalRebatableValue,
            dblTotalAcquiredRebatableValue,
        	dblTotalAPP,
        	dblTotalAGP,            
            dblRebatesMaintainingBal,
            dblEWalletRunningBal;

        if(intCutOffID > 0) then
                        SET strRebatesType = '';
            SET dblPersonalRebatesPercent = 0;
            SET dblRebateLevel1Percent = 0;
            SET dblRebateLevel2Percent = 0;
            SET dblRebateLevel3Percent = 0;
            SET dblRebateLevel4Percent = 0;
            SET dblRebateLevel5Percent = 0;
            SET dblRebateLevel6Percent = 0;
            SET dblRebateLevel7Percent = 0;
            SET dblRebateLevel8Percent = 0;
            SET dblRebateLevel9Percent = 0;
            SELECT 
              PersonalRebatesPercent,
              RebateLevel1Percent,
              RebateLevel2Percent,
              RebateLevel3Percent,
              RebateLevel4Percent,
              RebateLevel5Percent,
              RebateLevel6Percent,
              RebateLevel7Percent,
              RebateLevel8Percent,
              RebateLevel9Percent        	
            FROM packagerebates
            WHERE PackageID = intPackageID
            LIMIT 1
            INTO 
              dblPersonalRebatesPercent,
              dblRebateLevel1Percent,
              dblRebateLevel2Percent,
              dblRebateLevel3Percent,
              dblRebateLevel4Percent,
              dblRebateLevel5Percent,
              dblRebateLevel6Percent,
              dblRebateLevel7Percent,
              dblRebateLevel8Percent,
              dblRebateLevel9Percent;
            
            SET strRebatesType = '';
            SET dblRebatePercent = 0;
            if(intLevelNo = 0) then
                SET strRebatesType = 'Personal Rebates';
                SET dblRebatePercent = dblPersonalRebatesPercent;
            elseif(intLevelNo = 1) then
                SET strRebatesType = 'Rebates Level 1';
                SET dblRebatePercent = dblRebateLevel1Percent;
            elseif(intLevelNo = 2) then
                SET strRebatesType = 'Rebates Level 2';
                SET dblRebatePercent = dblRebateLevel2Percent;
            elseif(intLevelNo = 3) then
                SET strRebatesType = 'Rebates Level 3';
                SET dblRebatePercent = dblRebateLevel3Percent;
            elseif(intLevelNo = 4) then
                SET strRebatesType = 'Rebates Level 4';
                SET dblRebatePercent = dblRebateLevel4Percent;
            elseif(intLevelNo = 5) then
                SET strRebatesType = 'Rebates Level 5';
                SET dblRebatePercent = dblRebateLevel5Percent;
            elseif(intLevelNo = 6) then
                SET strRebatesType = 'Rebates Level 6';
                SET dblRebatePercent = dblRebateLevel6Percent;
            elseif(intLevelNo = 7) then
                SET strRebatesType = 'Rebates Level 7';
                SET dblRebatePercent = dblRebateLevel7Percent;
            elseif(intLevelNo = 8) then
                SET strRebatesType = 'Rebates Level 8';
                SET dblRebatePercent = dblRebateLevel8Percent;
            elseif(intLevelNo = 9) then
                SET strRebatesType = 'Rebates Level 9';
                SET dblRebatePercent = dblRebateLevel9Percent;
            end if;
            
            SET intLevelMax = 9;
            SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebatePercent/100);

                        if(dblRebates > 0) then
            
                                INSERT INTO ewalletledger SET
                    ComplanID = REBATES_COMPLAN_ID,
                    MemberID = intMainMemberID,
                    EarnedFromMemberID = intMainMemberID,
                    LevelNo = intLevelNo,
                    DateTimeEarned = NOW(),
                    EarnedMonth = MONTH(recCurrentDateTime),
                    EarnedYear = YEAR(recCurrentDateTime),
                    INAmount = dblRebates,
                    OUTAmount = 0,
                    OldBalance = dblEWalletRunningBal,
                    RunningBalance = (dblEWalletRunningBal + dblRebates),
                    Remarks = CONCAT(strRebatesType,' for the month of ', CAST(intCutOffMonth as CHAR), '-', CAST(intCutOffYear as CHAR)),
                    `Status` = 'Approved',
                    TransactionRefID = intMainMemberEntryID,
                    DateTimeCreated = curDateTime,
                    DateTimeUpdated = curDateTime;
                    
                                UPDATE `memberentry` SET
                    AccumulatedRewards = AccumulatedRewards + dblRebates
                WHERE MemberID = intMainMemberID;
				
                                UPDATE `memberentrycutoff` SET
                    IsRebatesGenerated = 1
                WHERE CutOffID = intCutOffID;

                                SET dblEWalletRunningBal = dblEWalletRunningBal + dblRebates;
                
                                DROP TEMPORARY TABLE IF EXISTS tblTempCollection;
                CREATE TEMPORARY TABLE tblTempCollection
                SELECT 
                    cutoff.`MemberEntryID`,
                    intLevelNo as LevelNo
                FROM `memberentrycutoff` as cutoff
                WHERE cutoff.`MemberEntryID` = intMainMemberEntryID;
                
                                SET strRankLevel1 = '';
                SET dblRankLevel1Percent = 0;
                SET dblRankLevel1APPRV = 0;
                SET dblRankLevel1AGPRV = 0;
                SET strRankLevel2 = '';
                SET dblRankLevel2Percent = 0;
                SET dblRankLevel2APPRV = 0;
                SET dblRankLevel2AGPRV = 0;
                SET strRankLevel3 = '';
                SET dblRankLevel3Percent = 0;
                SET dblRankLevel3APPRV = 0;
                SET dblRankLevel3AGPRV = 0;
                SET strRankLevel4 = '';
                SET dblRankLevel4Percent = 0;
                SET dblRankLevel4APPRV = 0;
                SET dblRankLevel4AGPRV = 0;
                SET strRankLevel5 = '';
                SET dblRankLevel5Percent = 0;
                SET dblRankLevel5APPRV = 0;
                SET dblRankLevel5AGPRV = 0;
                SET strRankLevel6 = '';
                SET dblRankLevel6Percent = 0;
                SET dblRankLevel6APPRV = 0;
                SET dblRankLevel6AGPRV = 0;
                SET strRankLevel7 = '';
                SET dblRankLevel7Percent = 0;
                SET dblRankLevel7APPRV = 0;
                SET dblRankLevel7AGPRV = 0;
                SET strRankLevel8 = '';
                SET dblRankLevel8Percent = 0;
                SET dblRankLevel8APPRV = 0;
                SET dblRankLevel8AGPRV = 0;
                SET strRankLevel9 = '';
                SET dblRankLevel9Percent = 0;
                SET dblRankLevel9APPRV = 0;
                SET dblRankLevel9AGPRV = 0;
    			SELECT 
                    pckrank.`RankLevel1`,
                    COALESCE(pckrank.`RankLevel1Percent`,0) as RankLevel1Percent,
                    COALESCE(pckrank.`RankLevel1APPRV`,0) as RankLevel1APPRV,
                    COALESCE(pckrank.`RankLevel1AGPRV`,0) as RankLevel1AGPRV,
                    pckrank.`RankLevel2`,
                    COALESCE(pckrank.`RankLevel2Percent`,0) as RankLevel2Percent,
                    COALESCE(pckrank.`RankLevel2APPRV`,0) as RankLevel2APPRV,
                    COALESCE(pckrank.`RankLevel2AGPRV`,0) as RankLevel2AGPRV,
                    pckrank.`RankLevel3`,
                    COALESCE(pckrank.`RankLevel3Percent`,0) as RankLevel3Percent,
                    COALESCE(pckrank.`RankLevel3APPRV`,0) as RankLevel3APPRV,
                    COALESCE(pckrank.`RankLevel3AGPRV`,0) as RankLevel3AGPRV,
                    pckrank.`RankLevel4`,
                    COALESCE(pckrank.`RankLevel4Percent`,0) as RankLevel4Percent,
                    COALESCE(pckrank.`RankLevel4APPRV`,0) as RankLevel4APPRV,
                    COALESCE(pckrank.`RankLevel4AGPRV`,0) as RankLevel4AGPRV,
                    pckrank.`RankLevel5`,
                    COALESCE(pckrank.`RankLevel5Percent`,0) as RankLevel5Percent,
                    COALESCE(pckrank.`RankLevel5APPRV`,0) as RankLevel5APPRV,
                    COALESCE(pckrank.`RankLevel5AGPRV`,0) as RankLevel5AGPRV,
                    pckrank.`RankLevel6`,
                    COALESCE(pckrank.`RankLevel6Percent`,0) as RankLevel6Percent,
                    COALESCE(pckrank.`RankLevel6APPRV`,0) as RankLevel6APPRV,
                    COALESCE(pckrank.`RankLevel6AGPRV`,0) as RankLevel6AGPRV,
                    pckrank.`RankLevel7`,
                    COALESCE(pckrank.`RankLevel7Percent`,0) as RankLevel7Percent,
                    COALESCE(pckrank.`RankLevel7APPRV`,0) as RankLevel7APPRV,
                    COALESCE(pckrank.`RankLevel7AGPRV`,0) as RankLevel7AGPRV,
                    pckrank.`RankLevel8`,
                    COALESCE(pckrank.`RankLevel8Percent`,0) as RankLevel8Percent,
                    COALESCE(pckrank.`RankLevel8APPRV`,0) as RankLevel8APPRV,
                    COALESCE(pckrank.`RankLevel8AGPRV`,0) as RankLevel8AGPRV,
                    pckrank.`RankLevel9`,
                    COALESCE(pckrank.`RankLevel9Percent`,0) as RankLevel9Percent,
                    COALESCE(pckrank.`RankLevel9APPRV`,0) as RankLevel9APPRV,
                    COALESCE(pckrank.`RankLevel9AGPRV`,0) as RankLevel9AGPRV
                FROM `packagerank` as pckrank 
                WHERE `pckrank`.`PackageID` = intPackageID
                INTO 
                    strRankLevel1,
                    dblRankLevel1Percent,
                    dblRankLevel1APPRV,
                    dblRankLevel1AGPRV,
                    strRankLevel2,
                    dblRankLevel2Percent,
                    dblRankLevel2APPRV,
                    dblRankLevel2AGPRV,
                    strRankLevel3,
                    dblRankLevel3Percent,
                    dblRankLevel3APPRV,
                    dblRankLevel3AGPRV,
                    strRankLevel4,
                    dblRankLevel4Percent,
                    dblRankLevel4APPRV,
                    dblRankLevel4AGPRV,
                    strRankLevel5,
                    dblRankLevel5Percent,
                    dblRankLevel5APPRV,
                    dblRankLevel5AGPRV,
                    strRankLevel6,
                    dblRankLevel6Percent,
                    dblRankLevel6APPRV,
                    dblRankLevel6AGPRV,
                    strRankLevel7,
                    dblRankLevel7Percent,
                    dblRankLevel7APPRV,
                    dblRankLevel7AGPRV,
                    strRankLevel8,
                    dblRankLevel8Percent,
                    dblRankLevel8APPRV,
                    dblRankLevel8AGPRV,
                    strRankLevel9,
                    dblRankLevel9Percent,
                    dblRankLevel9APPRV,
                    dblRankLevel9AGPRV;
                
                                SET intLevelLimit = 5;
                if(dblTotalAPP >= dblRankLevel9APPRV AND dblTotalAGP >= dblRankLevel9AGPRV) then
                	SET intLevelLimit = 9;
                elseif(dblTotalAPP >= dblRankLevel8APPRV AND dblTotalAGP >= dblRankLevel8AGPRV) then
                	SET intLevelLimit = 8;
                elseif(dblTotalAPP >= dblRankLevel7APPRV AND dblTotalAGP >= dblRankLevel7AGPRV) then
                	SET intLevelLimit = 7;
                elseif(dblTotalAPP >= dblRankLevel6APPRV AND dblTotalAGP >= dblRankLevel6AGPRV) then
                	SET intLevelLimit = 6;
                elseif(dblTotalAPP >= dblRankLevel5APPRV AND dblTotalAGP >= dblRankLevel5AGPRV) then
                	SET intLevelLimit = 5;
                elseif(dblTotalAPP >= dblRankLevel4APPRV AND dblTotalAGP >= dblRankLevel4AGPRV) then
                	SET intLevelLimit = 4;
                elseif(dblTotalAPP >= dblRankLevel3APPRV AND dblTotalAGP >= dblRankLevel3AGPRV) then
                	SET intLevelLimit = 3;
                elseif(dblTotalAPP >= dblRankLevel2APPRV AND dblTotalAGP >= dblRankLevel2AGPRV) then
                	SET intLevelLimit = 2;
                elseif(dblTotalAPP >= dblRankLevel1APPRV AND dblTotalAGP >= dblRankLevel1AGPRV) then
                	SET intLevelLimit = 1;
                end if;				

                GetIssueRebatesOtherLevel:WHILE intLevelNo <= intLevelLimit DO

                                        OPEN cursorSponsorshipRebates;
                    SELECT FOUND_ROWS() INTO cursorSponsorshipRebatesCount;    	
                    if(cursorSponsorshipRebatesCount > 0) then
                        GetSponsoredLevel:WHILE cursorSponsorshipRebatesCntr < cursorSponsorshipRebatesCount DO

                                                        SET intCutOffID = 0;
                            SET intMemberEntryID = 0;
                            SET intMemberID = 0;
                            SET intSponsorEntryID = 0;
                            SET intSponsorMemberID = 0;
                            SET intAcquiredByEntryID = 0;
                            SET dblTotalRebatableValue = 0;
                            SET dblTotalAcquiredRebatableValue = 0;
                            SET dblRebatesMaintainingBal = 0;
                            FETCH cursorSponsorshipRebates
                            INTO 
                                intCutOffID,
                                intMemberEntryID,
                                intMemberID,
                                intSponsorEntryID,
                                intSponsorMemberID,
                                intAcquiredByEntryID,
                                dblTotalRebatableValue,
                                dblTotalAcquiredRebatableValue,
                                dblRebatesMaintainingBal;
                        	
                            if(dblTotalRebatableValue < dblRebatesMaintainingBal) then

                            	                                SET intCutOffID = 0;
                            	SET intMemberEntryID = intAcquiredByEntryID;
                                SET intMemberID = 0;
                                SET intSponsorEntryID = 0;
                                SET intSponsorMemberID = 0;
                                SET intAcquiredByEntryID = 0;
                                SET dblTotalRebatableValue = 0;
                                SET dblTotalAcquiredRebatableValue = 0;
                                SET dblRebatesMaintainingBal = 0;
                                SELECT 
                                    cutoff.`CutOffID`,
                                    cutoff.`MemberEntryID`,
                                    mentry.`MemberID`,
                                    mentry.`SponsorEntryID`,
                                    sprentry.`MemberID` as SponsorMemberID,
                                    cutoff.AcquiredByEntryID,
                                    cutoff.`TotalRebatableValue`,
                                    cutoff.`TotalAcquiredRebatableValue`,
                                    cutoff.`MaintainingBalance`
                                FROM `memberentrycutoff` as cutoff
                                INNER JOIN `memberentry` as mentry ON (mentry.`EntryID` = cutoff.`MemberEntryID`)
                                INNER JOIN `memberentry` as sprentry ON (sprentry.`EntryID` = mentry.`SponsorEntryID`)
                                WHERE cutoff.`MemberEntryID` = intMemberEntryID
                                AND MONTH(cutoff.EndDate) = intCutOffMonth
                                AND YEAR(cutoff.EndDate) = intCutOffYear
                                INTO 
                                    intCutOffID,
                                    intMemberEntryID,
                                    intMemberID,
                                    intSponsorEntryID,
                                    intSponsorMemberID,
                                    intAcquiredByEntryID,
                                    dblTotalRebatableValue,
                                    dblTotalAcquiredRebatableValue,
                                    dblRebatesMaintainingBal;                                   
                                
                            end if;
                            
                            SET strRebatesType = '';
                            SET dblRebates = 0;
                            if((intLevelNo + 1) = 0) then
                                SET strRebatesType = 'Personal Rebates';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblPersonalRebatesPercent/100);
                            elseif((intLevelNo + 1) = 1) then
                                SET strRebatesType = 'Rebates Level 1';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebateLevel1Percent/100);
                            elseif((intLevelNo + 1) = 2) then
                                SET strRebatesType = 'Rebates Level 2';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebateLevel2Percent/100);
                            elseif((intLevelNo + 1) = 3) then
                                SET strRebatesType = 'Rebates Level 3';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebateLevel3Percent/100);
                            elseif((intLevelNo + 1) = 4) then
                                SET strRebatesType = 'Rebates Level 4';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebateLevel4Percent/100);
                            elseif((intLevelNo + 1) = 5) then
                                SET strRebatesType = 'Rebates Level 5';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebateLevel5Percent/100);
                            elseif((intLevelNo + 1) = 6) then
                                SET strRebatesType = 'Rebates Level 6';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebateLevel6Percent/100);
                            elseif((intLevelNo + 1) = 7) then
                                SET strRebatesType = 'Rebates Level 7';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebateLevel7Percent/100);
                            elseif((intLevelNo + 1) = 8) then
                                SET strRebatesType = 'Rebates Level 8';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebateLevel8Percent/100);
                            elseif((intLevelNo + 1) = 9) then
                                SET strRebatesType = 'Rebates Level 9';
                                SET dblRebates = dblTotalAcquiredRebatableValue * (dblRebateLevel9Percent/100);
                            end if;                        
                            
                            if(dblRebates > 0) then
                            
                                                                INSERT INTO ewalletledger SET
                                    ComplanID = REBATES_COMPLAN_ID,
                                    MemberID = intMainMemberID,
                                    EarnedFromMemberID = intMemberID,
                                    LevelNo = (intLevelNo + 1),
                                    DateTimeEarned = NOW(),
                                    EarnedMonth = MONTH(recCurrentDateTime),
                                    EarnedYear = YEAR(recCurrentDateTime),
                                    INAmount = dblRebates,
                                    OUTAmount = 0,
                                    OldBalance = dblEWalletRunningBal,
                                    RunningBalance = (dblEWalletRunningBal + dblRebates),
                                    Remarks = CONCAT(strRebatesType,' for the month of ', CAST(intCutOffMonth as CHAR), '-', CAST(intCutOffYear as CHAR)),
                                    `Status` = 'Approved',
                                    TransactionRefID = intMainMemberEntryID,
                                    DateTimeCreated = curDateTime,
                                    DateTimeUpdated = curDateTime;
                                    
                                                                UPDATE `memberentry` SET
                                    AccumulatedRewards = AccumulatedRewards + dblRebates
                                WHERE MemberID = intMainMemberID;

                                                                SET dblEWalletRunningBal = dblEWalletRunningBal + dblRebates;
                                
                            end if;
                            
                            
                                                        INSERT INTO tblTempCollection SET
                                MemberEntryID = intMemberEntryID,
                                LevelNo = intLevelNo + 1;
                            
                            SET cursorSponsorshipRebatesCntr = cursorSponsorshipRebatesCntr + 1;
                            
                        END WHILE; 
                    end if;
                    
					CLOSE cursorSponsorshipRebates;
                    
                                        SET intLevelNo = intLevelNo + 1;
                
                END WHILE; 
            end if;
            
        end if;
        
                SET intcoachmicjavCutOffID = intCutOffID;
            
    END WHILE; 
    DROP TEMPORARY TABLE IF EXISTS tblTempCollection;
    DROP TEMPORARY TABLE IF EXISTS tblTempCutOff;
    
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGetDashboardFigures`(IN `recCurrentDateTime` DATETIME)
BEGIN

	DECLARE BRONZE_PACKAGE_ID BIGINT(13) DEFAULT 1;
	DECLARE SILVER_PACKAGE_ID BIGINT(13) DEFAULT 2;
	DECLARE GOLD_PACKAGE_ID BIGINT(13) DEFAULT 3;
        
	SELECT
    	COALESCE((SELECT COUNT(*)
        	FROM `memberentry`
            WHERE PackageID = BRONZE_PACKAGE_ID)
        ,0) as BronzeMemberCount,
    	COALESCE((SELECT COUNT(*)
        	FROM `memberentry`
            WHERE PackageID = SILVER_PACKAGE_ID)
        ,0) as SilverMemberCount,
    	COALESCE((SELECT COUNT(*)
        	FROM `memberentry`
            WHERE PackageID = GOLD_PACKAGE_ID)
        ,0) as GoldMemberCount,
    	COALESCE((SELECT COUNT(*)
              FROM `codegeneration` cg
              INNER JOIN `codegenerationbatch` cgb ON (cgb.BatchID = cg.BatchID)
              WHERE cgb.`Status` = 'Approved'
            )
        ,0) as CodeCount,
    	COALESCE((SELECT COUNT(*)
              FROM `codegeneration` cg
              INNER JOIN `codegenerationbatch` cgb ON (cgb.BatchID = cg.BatchID)
              WHERE cgb.`Status` = 'Approved'
              AND COALESCE(cg.UsedByEntryID,0) > 0
            )
        ,0) as CodeUsed,
    	0 as EWalletBalance,
        
    	COALESCE((SELECT SUM(COALESCE(TotalAmountDue,0)) as TotalAmountDue
              FROM `order`
              WHERE `Status` = 'Approved'
            )
        ,0) as TotalSales,
    	COALESCE((SELECT COUNT(*) as Cnt
              FROM `product`
              WHERE `Status` = 'Active'
            )
        ,0) as ProductCount
        ;
        
 
 
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGetMemberMatchingEntries`(
        IN `recEntryID` BIGINT,
        IN `recCurrentDateTime` DATETIME
    )
BEGIN
    SELECT 
        mentrymatch.`MatchID`,
        mentrymatch.`HeadEntryID` as SponsorEntryID,
        hmentry.`EntryCode` as SponsorEntryCode,
        CONCAT(COALESCE(hmember.FirstName,''),' ',if(COALESCE(hmember.MiddleName,'') != '', CONCAT(LEFT(COALESCE(hmember.MiddleName,''),1),'. '),''),COALESCE(hmember.LastName,'')) as SponsorMemberName,
        mentrymatch.`EntryID`,
        mentry.`EntryCode`,
        CONCAT(COALESCE(member.FirstName,''),' ',if(COALESCE(member.MiddleName,'') != '', CONCAT(LEFT(COALESCE(member.MiddleName,''),1),'. '),''),COALESCE(member.LastName,'')) as EntryMemberName,
        mpckg.`Package` as EntryPackage,
        mentrymatch.`MatchPosition`,
        mentrymatch.`LevelNo`,
        mentrymatch.`BPV`,
        COALESCE(mentrymatch.`LRunningBalance`,0) as LRunningBalance,
        COALESCE(mentrymatch.`RRunningBalance`,0) as RRunningBalance,
        mentrymatch.`Remarks`
    FROM `memberentrymatch` mentrymatch
    INNER JOIN `memberentry` hmentry ON (mentrymatch.`HeadEntryID` = hmentry.`EntryID`)
    INNER JOIN `member` hmember ON (hmentry.`MemberID` = hmember.`MemberID`)
    INNER JOIN `memberentry` mentry ON (mentrymatch.`EntryID` = mentry.`EntryID`)
    INNER JOIN `member` ON (mentry.`MemberID` = member.`MemberID`)
    INNER JOIN `package` mpckg ON (mentry.`PackageID` = mpckg.`PackageID`)
    WHERE mentrymatch.`HeadEntryID` = recEntryID
    ORDER BY mentrymatch.`MatchID` DESC;
 
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spSetAccumulatedOrder`(IN `recOrderID` BIGINT, IN `recCurrentDateTime` DATETIME)
BEGIN
    DECLARE curDateTime DATETIME DEFAULT NOW();
	DECLARE REBATES_COMPLAN_ID BIGINT(13) DEFAULT 4;
	DECLARE intCompanyEntryID BIGINT DEFAULT 1;
    DECLARE intEntryID BIGINT DEFAULT 0;
    DECLARE intOrderID BIGINT DEFAULT 0;
    DECLARE dblTotalAmountDue DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRebatableValue DOUBLE(13,3) DEFAULT 0;
    DECLARE dblPersonalRunningBalance DOUBLE(13,3) DEFAULT 0;
    DECLARE dblGroupRunningBalance DOUBLE(13,3) DEFAULT 0;
    DECLARE intCutOffID BIGINT DEFAULT 0;
 	DECLARE dblTotalPurchases DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblMaintainingBalance DOUBLE(13,3) DEFAULT 0;
    DECLARE intCurHeadEntryID BIGINT DEFAULT 0;
    DECLARE intNextHeadEntryID BIGINT DEFAULT 0;
    DECLARE intLevelNo INT DEFAULT 0;
    
        SET intEntryID = 0;
    SET intOrderID = 0;
    SET dblTotalAmountDue = 0;
    SET dblRebatableValue = 0;
    SET dblPersonalRunningBalance = 0;
    SET dblGroupRunningBalance = 0;
    SELECT 
 		mentry.`EntryID`,
 		`order`.`OrderID`,
        `order`.`TotalAmountDue`,
 		`order`.`TotalRebatableValue`,
        COALESCE((
        		SELECT PersonalRunningBalance
                FROM memberentryorder
                WHERE HeadEntryID = mentry.`EntryID`
                ORDER BY AccumulatedOrderID DESC
                LIMIT 1
        	)
        ,0) as PersonalRunningBalance,
        COALESCE((
        		SELECT PersonalRunningBalance
                FROM memberentryorder
                WHERE HeadEntryID = mentry.`EntryID`
                ORDER BY AccumulatedOrderID DESC
                LIMIT 1
        	)
        ,0) as GroupRunningBalance
    FROM `order`
    INNER JOIN `memberentry` mentry ON (mentry.`EntryID` = `order`.`CustomerEntryID`)
    WHERE `order`.`OrderID` = recOrderID
    AND `order`.`CustomerType` = 'Member'
    LIMIT 1
    INTO 
    	intEntryID,
        intOrderID,
        dblTotalAmountDue,
        dblRebatableValue,
        dblPersonalRunningBalance,
        dblGroupRunningBalance;
        
	    SET intLevelNo = 0;
 	if(dblRebatableValue > 0) then
 		INSERT INTO memberentryorder SET
          HeadEntryID = intEntryID,
          EntryID = intEntryID,
          OrderID = intOrderID,
          LevelNo = intLevelNo,
          RebatableValue = dblRebatableValue,
          PersonalRunningBalance = dblPersonalRunningBalance + dblRebatableValue,
          GroupRunningBalance = dblGroupRunningBalance; 

       	        SET intCutOffID = 0;
        SELECT CutOffID
        FROM memberentrycutoff
		WHERE MemberEntryID = intEntryID
  		AND MONTH(EndDate) = MONTH(recCurrentDateTime)
        AND YEAR(EndDate) = YEAR(recCurrentDateTime)
        INTO
        	intCutOffID;
        
        if(intCutOffID <= 0) then
          INSERT INTO memberentrycutoff (
              MemberEntryID,
              AcquiredByEntryID,
              StartDate,
              EndDate,
              TotalPurchases,
              TotalRebatableValue,
              MaintainingBalance,
              TotalAcquiredRebatableValue,
              Remarks,
              IsRebatesGenerated,
              DateTimeCreated)
          SELECT
            mbrentry.EntryID,
            NULL as AcquiredByEntryID,
            DATE_FORMAT(recCurrentDateTime,'%Y-%m-01') as StartDate,
            LAST_DAY(recCurrentDateTime) as EndDate,
            0 as TotalPurchases,
            0 as TotalRebatableValue,
            COALESCE((
                      SELECT
                          RebatesMaintainingBal
                      FROM `packagerebates`
                      WHERE PackageID = mbrentry.PackageID
                      LIMIT 1
                      )
            ,1500) as MaintainingBalance,
            0 as TotalAcquiredRebatableValue,
            '' as Remarks,
            0 as IsRebatesGenerated,
            NOW() as DateTimeCreated     
          FROM `memberentry` as mbrentry
          WHERE mbrentry.`EntryID` = intEntryID;
        end if;

                UPDATE memberentrycutoff SET
  			TotalPurchases = TotalPurchases + dblTotalAmountDue,
  			TotalRebatableValue = TotalRebatableValue + dblRebatableValue,
  			DateTimeUpdated = recCurrentDateTime
		WHERE MemberEntryID = intEntryID
  		AND MONTH(EndDate) = MONTH(recCurrentDateTime)
        AND YEAR(EndDate) = YEAR(recCurrentDateTime);
        
                CALL spSetMemberEntryRank(intEntryID, recCurrentDateTime);   
    end if;
 
	SET intCurHeadEntryID = intEntryID;
    LoopAccuEntry:WHILE intCurHeadEntryID > 0 AND intCurHeadEntryID <> intCompanyEntryID DO 
                SET intLevelNo = intLevelNo + 1;
        SET intNextHeadEntryID = 0;
        SET dblPersonalRunningBalance = 0;
        SET dblGroupRunningBalance = 0;
        SELECT 
            mentry.`SponsorEntryID`,
            COALESCE((
                    SELECT PersonalRunningBalance
                    FROM memberentryorder
                    WHERE HeadEntryID = mentry.`SponsorEntryID`
                    ORDER BY AccumulatedOrderID DESC
                    LIMIT 1
                )
            ,0) as PersonalRunningBalance,
            COALESCE((
                    SELECT GroupRunningBalance
                    FROM memberentryorder
                    WHERE HeadEntryID = mentry.`SponsorEntryID`
                    ORDER BY AccumulatedOrderID DESC
                    LIMIT 1
                )
            ,0) as GroupRunningBalance
        FROM `memberentry` mentry
        WHERE mentry.`EntryID` = intCurHeadEntryID
        LIMIT 1
        INTO 
            intNextHeadEntryID,
            dblPersonalRunningBalance,
            dblGroupRunningBalance;
                if(intNextHeadEntryID > 0 AND dblRebatableValue > 0) then
            INSERT INTO memberentryorder SET
              HeadEntryID = intNextHeadEntryID,
              EntryID = intEntryID,
              OrderID = intOrderID,
              LevelNo = intLevelNo,
              RebatableValue = dblRebatableValue,
              PersonalRunningBalance = dblPersonalRunningBalance,
              GroupRunningBalance = dblGroupRunningBalance + dblRebatableValue;             

                            CALL spSetMemberEntryRank(intNextHeadEntryID, recCurrentDateTime);   

        end if;
       
        SET intCurHeadEntryID = intNextHeadEntryID;
           
        if(intCurHeadEntryID = intCompanyEntryID) then
        	LEAVE LoopAccuEntry;
        end if;
           
    END WHILE;      
 
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGenerateRankRebates`(IN `recOrderID` BIGINT(20), IN `recCurrentDateTime` DATETIME)
BEGIN

    DECLARE curDateTime DATETIME DEFAULT NOW();
    DECLARE RANK_REBATES_COMPLAN_ID BIGINT(13) DEFAULT 5;
  	DECLARE intCompanyEntryID BIGINT DEFAULT 1;
    DECLARE intOrderEntryID BIGINT DEFAULT 0;
    DECLARE intOrderMemberID BIGINT DEFAULT 0;
    DECLARE intMemberID BIGINT DEFAULT 0;
    DECLARE intPackageID BIGINT DEFAULT 0;
    DECLARE intRankLevel BIGINT DEFAULT 0;
    DECLARE strRank VARCHAR(50) DEFAULT '';
    DECLARE intOrderID BIGINT DEFAULT 0;
    DECLARE strOrderNo VARCHAR(50) DEFAULT '';
    DECLARE dblTotalAmountDue DOUBLE(13,3) DEFAULT 0;
    DECLARE dblTotalRebatableValue DOUBLE(13,3) DEFAULT 0;
    DECLARE dblOldSponsorEWalletBal DOUBLE(13,3) DEFAULT 0;
    DECLARE intCurHeadEntryID BIGINT DEFAULT 0;
    DECLARE intNextHeadEntryID BIGINT DEFAULT 0;
    DECLARE intLevelNo INT DEFAULT 0;
    DECLARE dblRebates DOUBLE(13,3) DEFAULT 0;
	
 	DECLARE strRankLevel VARCHAR(150) DEFAULT '';
 	DECLARE strRankLevel1 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel1Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel2 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel2Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel3 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel3Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel4 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel4Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel5 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel5Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel6 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel6Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel7 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel7Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel8 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel8Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel9 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel9Percent DOUBLE(13,3) DEFAULT 0;
    
        SET intOrderEntryID = 0;
    SET intOrderMemberID = 0;
    SET intRankLevel = 0;
    SET strRank = '';
    SET intPackageID = 0;
    SET intOrderID = 0;
    SET strOrderNo = '';
    SET dblTotalAmountDue = 0;
    SET dblTotalRebatableValue = 0;
    SET dblOldSponsorEWalletBal = 0;
    SELECT 
        mentry.`EntryID`,
        mentry.MemberID,
        mentry.RankLevel,
        mentry.Rank,
        mentry.`PackageID`,
        `order`.`OrderID`,
        `order`.`OrderNo`,
        `order`.`TotalAmountDue`,
        `order`.`TotalRebatableValue`,
        COALESCE((SELECT COALESCE(RunningBalance,0) as Balance
                  FROM ewalletledger
                  WHERE MemberID = mentry.MemberID
                  ORDER BY LedgerID DESC,
                  DateTimeEarned DESC 
                  LIMIT 1 
    			)
        ,0) as SponsorEWalletBalance
    FROM `order`
    INNER JOIN `memberentry` mentry ON (mentry.`MemberID` = `order`.`CustomerMemberID`)
    WHERE `order`.`OrderID` = recOrderID
    AND `order`.`CustomerType` = 'Member'
    LIMIT 1
    INTO 
        intOrderEntryID,
        intOrderMemberID,
        intRankLevel,
        strRank,
        intPackageID,
        intOrderID,
        strOrderNo,
        dblTotalAmountDue,
        dblTotalRebatableValue,
        dblOldSponsorEWalletBal;
        SET intCurHeadEntryID = intOrderEntryID;
    LoopRankRebatesEntry:WHILE intCurHeadEntryID > 0 AND intCurHeadEntryID <> intCompanyEntryID DO 
                SET intLevelNo = intLevelNo + 1;
        SET intNextHeadEntryID = 0;
        SET intMemberID = 0;
        SET intRankLevel = 0;
        SET strRank = '';
        SET intPackageID = 0;
        SET dblOldSponsorEWalletBal = 0;
        SELECT 
            mentry.`ParentEntryID`,
            pentry.MemberID,
            pentry.RankLevel,
            pentry.Rank,
            pentry.`PackageID`,
        	COALESCE((SELECT COALESCE(RunningBalance,0) as Balance
        			FROM ewalletledger
        			WHERE MemberID = mentry.`ParentEntryID`
                  	ORDER BY LedgerID DESC,
                  	DateTimeEarned DESC
        			LIMIT 1 
        		)
        	,0) as SponsorEWalletBalance
        FROM `memberentry` mentry
        INNER JOIN `memberentry` pentry ON (pentry.`EntryID` = mentry.`ParentEntryID`)
        WHERE mentry.`EntryID` = intCurHeadEntryID
        LIMIT 1
        INTO 
            intNextHeadEntryID,
            intMemberID,
            intRankLevel,
            strRank,
            intPackageID,
            dblOldSponsorEWalletBal;
                SET strRankLevel1 = '';
        SET dblRankLevel1Percent = 0;
        SET strRankLevel2 = '';
        SET dblRankLevel2Percent = 0;
        SET strRankLevel3 = '';
        SET dblRankLevel3Percent = 0;
        SET strRankLevel4 = '';
        SET dblRankLevel4Percent = 0;
        SET strRankLevel5 = '';
        SET dblRankLevel5Percent = 0;
        SET strRankLevel6 = '';
        SET dblRankLevel6Percent = 0;
        SET strRankLevel7 = '';
        SET dblRankLevel7Percent = 0;
        SET strRankLevel8 = '';
        SET dblRankLevel8Percent = 0;
        SET strRankLevel9 = '';
        SET dblRankLevel9Percent = 0;
        SELECT 
            pckrank.`RankLevel1`,
            COALESCE(pckrank.`RankLevel1Percent`,0) as RankLevel1Percent,
            pckrank.`RankLevel2`,
            COALESCE(pckrank.`RankLevel2Percent`,0) as RankLevel2Percent,
            pckrank.`RankLevel3`,
            COALESCE(pckrank.`RankLevel3Percent`,0) as RankLevel3Percent,
            pckrank.`RankLevel4`,
            COALESCE(pckrank.`RankLevel4Percent`,0) as RankLevel4Percent,
            pckrank.`RankLevel5`,
            COALESCE(pckrank.`RankLevel5Percent`,0) as RankLevel5Percent,
            pckrank.`RankLevel6`,
            COALESCE(pckrank.`RankLevel6Percent`,0) as RankLevel6Percent,
            pckrank.`RankLevel7`,
            COALESCE(pckrank.`RankLevel7Percent`,0) as RankLevel7Percent,
            pckrank.`RankLevel8`,
            COALESCE(pckrank.`RankLevel8Percent`,0) as RankLevel8Percent,
            pckrank.`RankLevel9`,
            COALESCE(pckrank.`RankLevel9Percent`,0) as RankLevel9Percent
        FROM `packagerank` as pckrank 
        WHERE pckrank.`PackageID` = intPackageID
        LIMIT 1
        INTO 
            strRankLevel1,
            dblRankLevel1Percent,
            strRankLevel2,
            dblRankLevel2Percent,
            strRankLevel3,
            dblRankLevel3Percent,
            strRankLevel4,
            dblRankLevel4Percent,
            strRankLevel5,
            dblRankLevel5Percent,

            strRankLevel6,
            dblRankLevel6Percent,
            strRankLevel7,
            dblRankLevel7Percent,
            strRankLevel8,
            dblRankLevel8Percent,
            strRankLevel9,
            dblRankLevel9Percent;
  		
        SET strRankLevel = '';
    	SET dblRebates = 0;
		if(intLevelNo = 1) then
            SET strRankLevel = strRankLevel1;
            SET dblRebates = dblTotalRebatableValue * (dblRankLevel1Percent / 100);
		elseif(intLevelNo = 2) then
            SET strRankLevel = strRankLevel2;
            SET dblRebates = dblTotalRebatableValue * (dblRankLevel2Percent / 100);
		elseif(intLevelNo = 3) then
            SET strRankLevel = strRankLevel3;
            SET dblRebates = dblTotalRebatableValue * (dblRankLevel3Percent / 100);
		elseif(intLevelNo = 4) then
            SET strRankLevel = strRankLevel4;
            SET dblRebates = dblTotalRebatableValue * (dblRankLevel4Percent / 100);
		elseif(intLevelNo = 5) then
            SET strRankLevel = strRankLevel5;
            SET dblRebates = dblTotalRebatableValue * (dblRankLevel5Percent / 100);
		elseif(intLevelNo = 6) then
            SET strRankLevel = strRankLevel6;
            SET dblRebates = dblTotalRebatableValue * (dblRankLevel6Percent / 100);
		elseif(intLevelNo = 7) then
            SET strRankLevel = strRankLevel7;
            SET dblRebates = dblTotalRebatableValue * (dblRankLevel7Percent / 100);
		elseif(intLevelNo = 8) then
            SET strRankLevel = strRankLevel8;
            SET dblRebates = dblTotalRebatableValue * (dblRankLevel8Percent / 100);
		elseif(intLevelNo = 9) then
            SET strRankLevel = strRankLevel9;
            SET dblRebates = dblTotalRebatableValue * (dblRankLevel9Percent / 100);
        end if;
 		        if(dblRebates > 0) then
            if(intLevelNo <= intRankLevel) then
                INSERT INTO ewalletledger SET
                    ComplanID = RANK_REBATES_COMPLAN_ID,
                    MemberID = intMemberID,
                    EarnedFromMemberID = intOrderMemberID,
                    LevelNo = intLevelNo,
                    DateTimeEarned = recCurrentDateTime,
                    EarnedMonth = MONTH(recCurrentDateTime),
                    EarnedYear = YEAR(recCurrentDateTime),
                    INAmount = dblRebates,
                    OUTAmount = 0,
                    OldBalance = dblOldSponsorEWalletBal,
                    RunningBalance = (dblOldSponsorEWalletBal + dblRebates),
                    Remarks = CONCAT('Rank Rebates Level ', intLevelNo ,' on Order No. ',strOrderNo),
                    `Status` = 'Approved',
                    TransactionRefID = intOrderID,
                    DateTimeCreated = recCurrentDateTime,
                    DateTimeUpdated = recCurrentDateTime;
            else
                INSERT INTO ewalletledger SET
                    ComplanID = RANK_REBATES_COMPLAN_ID,
                    MemberID = intCompanyEntryID,
                    EarnedFromMemberID = intOrderMemberID,
                    MissedByEntryID = intMemberID,
                    LevelNo = intLevelNo,
                    DateTimeEarned = recCurrentDateTime,
                    EarnedMonth = MONTH(recCurrentDateTime),
                    EarnedYear = YEAR(recCurrentDateTime),
                    INAmount = dblRebates,
                    OUTAmount = 0,
                    OldBalance = dblOldSponsorEWalletBal,
                    RunningBalance = (dblOldSponsorEWalletBal + dblRebates),
                    Remarks = CONCAT('Rank Rebates Level ', intLevelNo ,' on Order No. ',strOrderNo),
                    `Status` = 'Approved',
                    TransactionRefID = intOrderID,
                    DateTimeCreated = recCurrentDateTime,
                    DateTimeUpdated = recCurrentDateTime;
            end if; 
        end if; 
 
 		SET intCurHeadEntryID = intNextHeadEntryID;
 
        if(intCurHeadEntryID = intCompanyEntryID) then
            LEAVE LoopRankRebatesEntry;
        end if;
 
 	END WHILE;  
 
 
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGetAdminAlertLabels`()
BEGIN
 
 SELECT 
 (SELECT COUNT(*) FROM `order` WHERE Status = 'Unverified') as UnVerifiedOrder,
 (SELECT COUNT(*) FROM `order` WHERE Status = 'Verified') as VerifiedOrder,
 (SELECT COUNT(*) FROM `order` WHERE Status = 'Packed') as PackedOrder,
 (SELECT COUNT(*) FROM `order` WHERE Status = 'Shipped') as ShippedOrder,
 (SELECT COUNT(*) FROM `order` WHERE IsPaid = 0 AND Status != 'Cancelled' AND Status != 'Returned') as UnCollectedOrder;
  

END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGetMemberGenealogy`(IN `recEntryID` BIGINT, IN `recMaxLevel` INT)
BEGIN

    DECLARE intLevelCntr BIGINT DEFAULT 1;
    DECLARE intLevelMax BIGINT DEFAULT 15;
    DECLARE intPositionCntr BIGINT DEFAULT 0;
    DECLARE intEntryID BIGINT;
    DECLARE intLeftEntryID BIGINT;
    DECLARE intRightEntryID BIGINT;
    DECLARE intLevelNo BIGINT;
    DECLARE intCntr BIGINT DEFAULT 1;
    
    DECLARE cursorTmpRecCount BIGINT;
    DECLARE cursorTmpRecCntr BIGINT;
    DEClARE cursorTmp CURSOR FOR
      SELECT tmp.EntryID
      ,tmp.LevelNo
      FROM tblTempCollection tmp
      INNER JOIN `memberentry` mentry ON (mentry.`EntryID` = tmp.EntryID)
      WHERE tmp.LevelNo = (intLevelCntr - 1)
      ORDER BY tmp.EntryID ASC;
      
    if(COALESCE(recMaxLevel,0) > 0) then 
    	SET intLevelMax = recMaxLevel;
    else
    	SET intLevelMax = 100;
    end if;
	SET intCntr = 1;
    
    DROP TEMPORARY TABLE IF EXISTS tblTempCollection;
    CREATE TEMPORARY TABLE tblTempCollection
    SELECT 
    	EntryID
    	,0 as TreeSponsorID
    	,0 as LevelNo
        ,intCntr as Cntr
        ,'L' as MatrixPosition
    FROM `memberentry`
    WHERE EntryID = recEntryID
    LIMIT 1; 
     
    SET intLevelCntr = 1;
    GetLevelMemberEntry:WHILE intLevelCntr <= intLevelMax DO
    	
        OPEN cursorTmp;
    	SELECT FOUND_ROWS() INTO cursorTmpRecCount;
     
    	if(cursorTmpRecCount > 0) then
    		SET cursorTmpRecCntr = 0;
    		SET intPositionCntr = 0;
    		GetPosition:WHILE cursorTmpRecCntr < cursorTmpRecCount DO
     
                SET intEntryID = 0;
                SET intLevelNo = 0;
                FETCH cursorTmp
                INTO intEntryID, intLevelNo;
				
                if(intEntryID > 0) then
                	
                  	SET intLeftEntryID = 0;
                    SET intRightEntryID = 0;
                    SELECT 	
                    	COALESCE(LeftEntryID,0) as LeftEntryID,
                    	COALESCE(RightEntryID,0) as RightEntryID
                    FROM `memberentry`
                    WHERE EntryID = intEntryID
                    LIMIT 1
                    INTO 
                    	 intLeftEntryID,
                         intRightEntryID;
                
                                        SET intCntr = intCntr + 1;
                    if(intLeftEntryID > 0) then
                        INSERT INTO tblTempCollection
                        SELECT 
                            EntryID
                            ,ParentEntryID as TreeSponsorID
                            ,intLevelCntr as LevelNo
                            ,intCntr as Cntr
                            ,ParentPosition as MatrixPosition
                        FROM `memberentry`
                        WHERE ParentEntryID = intEntryID
                        AND ParentPosition = 'L'; 
                    else
                        INSERT INTO tblTempCollection
                        SELECT 
                            0 as EntryID
                            ,intEntryID as TreeSponsorID
                            ,intLevelCntr as LevelNo
                            ,intCntr as Cntr
                            ,'L' as MatrixPosition;
                    end if;
                                        SET intCntr = intCntr + 1;
                    if(intRightEntryID > 0) then
                        INSERT INTO tblTempCollection
                        SELECT 
                            EntryID
                            ,ParentEntryID as TreeSponsorID
                            ,intLevelCntr as LevelNo
                            ,intCntr as Cntr
                            ,ParentPosition as MatrixPosition
                        FROM `memberentry`
                        WHERE ParentEntryID = intEntryID
                        AND ParentPosition = 'R'; 
                    else
                        INSERT INTO tblTempCollection
                        SELECT 
                            0 as EntryID
                            ,intEntryID as TreeSponsorID
                            ,intLevelCntr as LevelNo
                            ,intCntr as Cntr
                            ,'R' as MatrixPosition;
                    end if;
    
                end if;
                SET cursorTmpRecCntr = cursorTmpRecCntr + 1;
     
            END WHILE;      
    	end if;     
    	CLOSE cursorTmp;
    	SET intLevelCntr = intLevelCntr + 1;
     
    END WHILE;     
    SELECT 
        COALESCE(tmp.EntryID,0) as EntryID
        ,COALESCE(mbrentry.`EntryCode`,'') as EntryCode
        ,COALESCE(mbrentry.`MemberID`,0) as MemberID
        ,COALESCE(mbr.`MemberNo`,'') as MemberNo
        ,CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberFullName
        ,COALESCE(tmp.TreeSponsorID,0) as TreeSponsorID
        ,COALESCE(pmbrentry.`EntryCode`,'') as TreeSponsorEntryCode
        ,COALESCE(pmbrentry.`MemberID`,0) as TreeSponsorMemberID
        ,COALESCE(pmbr.`MemberNo`,'') as TreeSponsorMemberNo
        ,CONCAT(COALESCE(pmbr.FirstName,''),' ',COALESCE(pmbr.LastName,'')) as TreeSponsorFullName
        ,COALESCE(mbrentry.`PackageID`,0) as PackageID
        ,COALESCE(pck.Package,'') as Package
        ,COALESCE(tmp.LevelNo,0) as LevelNo
        ,COALESCE(tmp.MatrixPosition,'') as MatrixPosition
        ,COALESCE(mbr.`Status`,'') as Status
        ,COALESCE(tmp.Cntr,0) as Cntr
    FROM tblTempCollection tmp
    LEFT JOIN `memberentry` mbrentry ON (mbrentry.`EntryID` = tmp.EntryID)
    LEFT JOIN `package` pck ON (mbrentry.`PackageID` = pck.`PackageID`)
    LEFT JOIN member mbr ON (mbr.MemberID = mbrentry.`MemberID`)
    LEFT JOIN `memberentry` pmbrentry ON (pmbrentry.`EntryID` = tmp.TreeSponsorID)
    LEFT JOIN member pmbr ON (pmbr.MemberID = pmbrentry.`MemberID`)
    WHERE tmp.LevelNo > 0
    ORDER BY tmp.LevelNo ASC,
    	tmp.Cntr ASC;
    DROP TEMPORARY TABLE IF EXISTS tblTempCollection;
    
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGetMemberDashboardFigures`(IN `recEntryID` BIGINT, IN `recCurrentDateTime` DATETIME)
BEGIN
 
     DECLARE BRONZE_PACKAGE_ID BIGINT(13) DEFAULT 1;
    DECLARE SILVER_PACKAGE_ID BIGINT(13) DEFAULT 2;
    DECLARE GOLD_PACKAGE_ID BIGINT(13) DEFAULT 3;
	SELECT 
        COALESCE((
        		SELECT LRunningBalance
                FROM memberentrymatch
                WHERE HeadEntryID = recEntryID
                ORDER BY MatchID DESC
                LIMIT 1
        	)
        ,0) as LRunningBalance,
        COALESCE((
        		SELECT RRunningBalance
                FROM memberentrymatch
                WHERE HeadEntryID = recEntryID
                ORDER BY MatchID DESC
                LIMIT 1
        	)
        ,0) as RRunningBalance,    
        COALESCE((
        		SELECT PersonalRunningBalance
                FROM memberentryorder
                WHERE HeadEntryID = recEntryID
                ORDER BY AccumulatedOrderID DESC
                LIMIT 1
        	)
        ,0) as PersonalRunningBalance,
        COALESCE((
        		SELECT GroupRunningBalance
                FROM memberentryorder
                WHERE HeadEntryID = recEntryID
                ORDER BY AccumulatedOrderID DESC
                LIMIT 1
        	)
        ,0) as GroupRunningBalance,
        COALESCE((
        		SELECT TotalEntryShare
                FROM memberentry
                WHERE EntryID = recEntryID
                LIMIT 1
        	)
        ,0) as TotalEntryShare,
        COALESCE((
        		SELECT AccumulatedRewards
                FROM memberentry
                WHERE EntryID = recEntryID
                LIMIT 1
        	)
        ,0) as TotalRewards,
        COALESCE((
        		SELECT AccumulatedEncashed
                FROM memberentry
                WHERE EntryID = recEntryID
                LIMIT 1
        	)
        ,0) as TotalEncashment,
        COALESCE((SELECT COALESCE(RunningBalance,0) as Balance
        	FROM ewalletledger
            INNER JOIN `memberentry` ON (ewalletledger.`MemberID` = `memberentry`.`MemberID`)
            WHERE memberentry.`EntryID` = recEntryID
            ORDER BY ewalletledger.LedgerID DESC
            LIMIT 1 
            )
        ,0) as AvailableEwallet;

 
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spSaveInventoryLog`(IN `RecCenterID` BIGINT, IN `RecProductID` BIGINT, IN `RecQty` DOUBLE, IN `RecType` VARCHAR(50), IN `RecStockOnHand` DOUBLE, IN `RecTransType` VARCHAR(50), IN `RecTransactionRefID` BIGINT, IN `RecDateTime` DATETIME, IN `RecRemarks` VARCHAR(250))
BEGIN
      
    DECLARE dblNewStockOnHand DOUBLE(13,3) DEFAULT 0;
 
 	SET dblNewStockOnHand = 0;
 	if(RecType = 'IN') then
 		SET dblNewStockOnHand = RecStockOnHand + RecQty;
	elseif(RecType = 'OUT') then
 		SET dblNewStockOnHand = RecStockOnHand - RecQty;
 	elseif(RecType = 'Remove From In') then
 		SET dblNewStockOnHand = RecStockOnHand - RecQty;
 	elseif(RecType = 'Remove From Out') then
 		SET dblNewStockOnHand = RecStockOnHand + RecQty;
 	end if;
 
 	if(RecType = 'IN' OR RecType = 'Remove From Out') then
      INSERT INTO productinvledger SET
          CenterID = RecCenterID, 
          ProductID = RecProductID, 
          QtyIn = RecQty, 
          QtyOut = 0, 
          OldStockOnHand = RecStockOnHand, 
          NewStockOnHand = dblNewStockOnHand, 
          TransType = RecTransType,
          TransactionRefID = RecTransactionRefID, 
          Remarks = RecRemarks,
          DateTimeCreated = RecDateTime;
          
 	elseif(RecType = 'OUT' OR RecType = 'Remove From In') then
    
      INSERT INTO productinvledger SET
          CenterID = RecCenterID, 
          ProductID = RecProductID, 
          QtyIn = 0, 
          QtyOut = RecQty, 
          OldStockOnHand = RecStockOnHand, 
          NewStockOnHand = dblNewStockOnHand, 
          TransType = RecTransType,
          TransactionRefID = RecTransactionRefID, 
          Remarks = RecRemarks,
          DateTimeCreated = RecDateTime;
    else
    
      INSERT INTO productinvledger SET
          CenterID = RecCenterID, 
          ProductID = RecProductID, 
          QtyIn = RecStockOnHand, 
          QtyOut = 0, 
          OldStockOnHand = RecStockOnHand, 
          NewStockOnHand = RecQty, 
          TransType = RecTransType,
          TransactionRefID = RecTransactionRefID, 
          Remarks = RecRemarks,
          DateTimeCreated = RecDateTime;
          
    end if;
    
  
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spSetMatchingDetail`(IN `recEntryID` BIGINT(20), IN `recPackageID` BIGINT, IN `recCurrentDateTime` DATETIME)
BEGIN
    DECLARE curDateTime DATETIME DEFAULT NOW();
    DECLARE intTopMostEntryID BIGINT DEFAULT 1;
    DECLARE dblPackagePrice DOUBLE(13,3) DEFAULT 0;
    DECLARE intCurHeadEntryID BIGINT DEFAULT 0;
    DECLARE intCurHeadPackageID BIGINT DEFAULT 0;
    DECLARE dblCurHeadPackagePrice DOUBLE(13,3) DEFAULT 0;
	DECLARE strPosition VARCHAR(1) DEFAULT 0;
    DECLARE intLevelNo INT DEFAULT 0;
    DECLARE intNextHeadEntryID BIGINT DEFAULT 0;
    DECLARE dblBPV DOUBLE(13,3) DEFAULT 0;
    DECLARE dblLRunningBalance DOUBLE(13,3) DEFAULT 0;
    DECLARE dblRRunningBalance DOUBLE(13,3) DEFAULT 0;
	    SET intLevelNo = 1;
    SET dblPackagePrice = 0;
    SET intCurHeadEntryID = 0;
    SET intCurHeadPackageID = 0;
    SET dblCurHeadPackagePrice = 0;
    SET strPosition = '';
    SET dblBPV = 0;
    SET dblLRunningBalance = 0;
    SET dblRRunningBalance = 0;
    SELECT 
    	pck.PackagePrice,
		mentry.`ParentEntryID`,
        hentry.`PackageID`,
        hpck.`PackagePrice` as HeadPackagePrice,
        mentry.`ParentPosition`,
        COALESCE(packagematchingcomm.`RequiredBPV`,0) as BPV,
        COALESCE((
        		SELECT LRunningBalance
                FROM memberentrymatch
                WHERE HeadEntryID = mentry.`ParentEntryID`
                ORDER BY MatchID DESC
                LIMIT 1
        	)
        ,0) as LRunningBalance,
        COALESCE((
        		SELECT RRunningBalance
                FROM memberentrymatch
                WHERE HeadEntryID = mentry.`ParentEntryID`
                ORDER BY MatchID DESC
                LIMIT 1
        	)
        ,0) as RRunningBalance
    FROM `memberentry` mentry
    INNER JOIN `package` pck ON (mentry.PackageID = pck.`PackageID`)
    INNER JOIN `memberentry` hentry ON (mentry.`ParentEntryID` = hentry.`EntryID`)
    INNER JOIN `package` hpck ON (hentry.PackageID = hpck.`PackageID`)
    INNER JOIN packagematchingcomm ON (packagematchingcomm.PackageID = mentry.`PackageID`)
    WHERE mentry.`EntryID` = recEntryID
    LIMIT 1
    INTO 
    	dblPackagePrice,
    	intCurHeadEntryID,
        intCurHeadPackageID,
        dblCurHeadPackagePrice,
    	strPosition,
        dblBPV,
        dblLRunningBalance,
        dblRRunningBalance;
    
    if(dblBPV > 0) then
                if(intCurHeadEntryID > 0 AND dblCurHeadPackagePrice >= dblPackagePrice) then
          INSERT INTO memberentrymatch SET
              HeadEntryID = intCurHeadEntryID,
              EntryID = recEntryID,
              LevelNo = intLevelNo,
              MatchPosition = strPosition,
              BPV = dblBPV,
              LRunningBalance = if(strPosition = 'L', dblBPV + dblLRunningBalance, dblLRunningBalance),
              RRunningBalance = if(strPosition = 'R', dblBPV + dblRRunningBalance, dblRRunningBalance),
              Remarks = '';
        end if;
        
        LoopMatchingEntry:WHILE intCurHeadEntryID > 0 AND intCurHeadEntryID <> intTopMostEntryID DO 
                        SET intLevelNo = intLevelNo + 1;
            SET intNextHeadEntryID = 0;
            SET intCurHeadPackageID = 0;
            SET dblCurHeadPackagePrice = 0;
            SET strPosition = '';
            SET dblLRunningBalance = 0;
            SET dblRRunningBalance = 0;
            SELECT 
                mentry.`ParentEntryID`,
                hentry.`PackageID`,
                hpck.`PackagePrice` as HeadPackagePrice,
                mentry.`ParentPosition`,
                COALESCE((
                        SELECT LRunningBalance
                        FROM memberentrymatch
                        WHERE HeadEntryID = mentry.`ParentEntryID`
                        ORDER BY MatchID DESC
                        LIMIT 1
                    )
                ,0) as LRunningBalance,
                COALESCE((
                        SELECT RRunningBalance
                        FROM memberentrymatch
                        WHERE HeadEntryID = mentry.`ParentEntryID`
                        ORDER BY MatchID DESC
                        LIMIT 1
                    )
                ,0) as RRunningBalance
            FROM `memberentry` mentry
            INNER JOIN `memberentry` hentry ON (mentry.`ParentEntryID` = hentry.`EntryID`)
            INNER JOIN `package` hpck ON (hentry.PackageID = hpck.`PackageID`)
            INNER JOIN packagematchingcomm ON (packagematchingcomm.PackageID = mentry.`PackageID`)
            WHERE mentry.`EntryID` = intCurHeadEntryID
            LIMIT 1
            INTO 
                intNextHeadEntryID,
                intCurHeadPackageID,
                dblCurHeadPackagePrice,
                strPosition,
                dblLRunningBalance,
                dblRRunningBalance;
            
                        if(intNextHeadEntryID > 0 AND dblCurHeadPackagePrice >= dblPackagePrice) then
              INSERT INTO memberentrymatch SET
                  HeadEntryID = intNextHeadEntryID,
                  EntryID = recEntryID,
                  LevelNo = intLevelNo,
                  MatchPosition = strPosition,
                  BPV = dblBPV,
                  LRunningBalance = if(strPosition = 'L', dblBPV + dblLRunningBalance, dblLRunningBalance),
                  RRunningBalance = if(strPosition = 'R', dblBPV + dblRRunningBalance, dblRRunningBalance),
                  Remarks = '';
            end if;
    		
            SET intCurHeadEntryID = intNextHeadEntryID;
      		
            if(intCurHeadEntryID = intTopMostEntryID) then
                LEAVE LoopMatchingEntry;
            end if;
        
        END WHILE; 
    end if;
     
     
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGenerateCutOff`(IN `recCurrentDateTime` DATE)
BEGIN
	    DELETE FROM memberentrycutoff
    WHERE MONTH(EndDate) = MONTH(recCurrentDateTime)
    AND YEAR(EndDate) = YEAR(recCurrentDateTime);
    
    	INSERT INTO memberentrycutoff (
    	MemberEntryID,
        AcquiredByEntryID,
        StartDate,
        EndDate,
        TotalPurchases,
        TotalRebatableValue,
        MaintainingBalance,
        TotalAcquiredRebatableValue,
        Remarks,
        IsRebatesGenerated,
        DateTimeCreated)
	SELECT
      mbrentry.EntryID,
      NULL as AcquiredByEntryID,
      DATE_FORMAT(recCurrentDateTime,'%Y-%m-01') as StartDate,
      LAST_DAY(recCurrentDateTime) as EndDate,
      COALESCE((
      			SELECT SUM(`order`.`TotalAmountDue`)
      			FROM `order`
      			WHERE CustomerEntryID = mbrentry.EntryID
      			AND DATE(OrderDateTime) BETWEEN DATE_FORMAT(recCurrentDateTime,'%Y-%m-01') AND LAST_DAY(recCurrentDateTime)
      			AND Status = 'Approved'
      			LIMIT 1
      			)
      ,0) as TotalPurchases,
      COALESCE((
      			SELECT SUM(`order`.`TotalRebatableValue`)
      			FROM `order`
      			WHERE CustomerEntryID = mbrentry.EntryID
      			AND DATE(OrderDateTime) BETWEEN DATE_FORMAT(recCurrentDateTime,'%Y-%m-01') AND LAST_DAY(recCurrentDateTime)
      			AND Status = 'Approved'
      			LIMIT 1
      			)
      ,0) as TotalRebatableValue,
      COALESCE((
      			SELECT
      				RebatesMaintainingBal
      			FROM `packagerebates`
      			WHERE PackageID = mbrentry.PackageID
                LIMIT 1
      			)
      ,1500) as MaintainingBalance,
      0 as TotalAcquiredRebatableValue,
      '' as Remarks,
      0 as IsRebatesGenerated,
      NOW() as DateTimeCreated     
	FROM `memberentry` as mbrentry
    WHERE mbrentry.DateTimeCreated <= LAST_DAY(recCurrentDateTime)
    ORDER BY mbrentry.`EntryID` ASC;
    
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGetMemberAccumulatedPurchases`(
        IN `recEntryID` BIGINT,
        IN `recCurrentDateTime` DATETIME
    )
BEGIN
    SELECT 
        morder.AccumulatedOrderID,
        morder.`HeadEntryID`,
        hmentry.`EntryCode` as HeadEntryCode,
        CONCAT(COALESCE(hmember.FirstName,''),' ',if(COALESCE(hmember.MiddleName,'') != '', CONCAT(LEFT(COALESCE(hmember.MiddleName,''),1),'. '),''),COALESCE(hmember.LastName,'')) as HeadMemberName,
        morder.`EntryID`,
        mentry.`EntryCode`,
        CONCAT(COALESCE(member.FirstName,''),' ',if(COALESCE(member.MiddleName,'') != '', CONCAT(LEFT(COALESCE(member.MiddleName,''),1),'. '),''),COALESCE(member.LastName,'')) as EntryMemberName,
        mpckg.`Package` as EntryPackage,
        morder.OrderID,
        COALESCE(`order`.`OrderNo`,'1') as OrderNo ,
        `order`.`OrderDateTime`,
        morder.`LevelNo`,
        morder.`RebatableValue`,
        morder.`PersonalRunningBalance`,
        morder.`GroupRunningBalance`,
        COALESCE(morder.`Remarks`,'') as Remarks
    FROM `memberentryorder` morder
    INNER JOIN `memberentry` hmentry ON (morder.`HeadEntryID` = hmentry.`EntryID`)
    INNER JOIN `member` hmember ON (hmentry.`MemberID` = hmember.`MemberID`)
    INNER JOIN `memberentry` mentry ON (morder.`EntryID` = mentry.`EntryID`)
    INNER JOIN `member` ON (mentry.`MemberID` = member.`MemberID`)
    INNER JOIN `package` mpckg ON (mentry.`PackageID` = mpckg.`PackageID`)
    LEFT JOIN `order` ON (morder.`OrderID` = `order`.`OrderID`)
    WHERE morder.`HeadEntryID` = recEntryID
    ORDER BY morder.AccumulatedOrderID DESC;
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGenerateMatchingCommission`(IN `recCurrentDateTime` DATETIME)
BEGIN

	DECLARE COMPLAN_ID BIGINT(13) DEFAULT 3;
	DECLARE curDateTime DATETIME DEFAULT NOW();
    
	DECLARE intEntryID BIGINT(13) DEFAULT 0;
	DECLARE intMemberID BIGINT(13) DEFAULT 0;
  	DECLARE dblLBPV DOUBLE(13,3) DEFAULT 0;
  	DECLARE dblRBPV DOUBLE(13,3) DEFAULT 0;
	DECLARE intPackageID BIGINT(13) DEFAULT 0;
    DECLARE dblRequiredBPV DOUBLE(13,3) DEFAULT 0;
    DECLARE dblPairingAmount DOUBLE(13,3) DEFAULT 0;
	DECLARE intMaxMatchPerDay BIGINT(13) DEFAULT 0;
    DECLARE intVoucherOnNthPair DOUBLE(13,3) DEFAULT 0;
	DECLARE dblEWalletRunningBal DOUBLE(13,3) DEFAULT 0;
    DECLARE dblAccuRewards DOUBLE(13,3) DEFAULT 0;
    DECLARE intGeneratedPair BIGINT(13) DEFAULT 0;
    
    DECLARE intMatchCntr INT DEFAULT 0;
    DECLARE dblAvailableMatch DOUBLE(13,3) DEFAULT 0;
    DECLARE isGenerateVoucher INT DEFAULT 0;
	DECLARE strVoucherNo VARCHAR(10) DEFAULT '';
    DECLARE isVoucherNotExist INT DEFAULT 0;
  	DECLARE cursorEntryMatchCount BIGINT DEFAULT 0;
  	DECLARE cursorEntryMatchCntr BIGINT DEFAULT 0;
    
    DEClARE cursorEntryMatch CURSOR FOR
    SELECT  
    	mentry.EntryID,
    	mentry.`MemberID`,
        COALESCE((
        		SELECT LRunningBalance
                FROM memberentrymatch
                WHERE HeadEntryID = mentry.`EntryID`
                ORDER BY MatchID DESC
                LIMIT 1
        	)
        ,0) as LRunningBalance,
        COALESCE((
        		SELECT RRunningBalance
                FROM memberentrymatch
                WHERE HeadEntryID = mentry.`EntryID`
                ORDER BY MatchID DESC
                LIMIT 1
        	)
        ,0) as RRunningBalance,
        mentry.`PackageID`,
        pckgmatchcom.`RequiredBPV`,
        pckgmatchcom.`PairingAmount`,
        pckgmatchcom.`MaxMatchPerDay`,
        pckgmatchcom.`VoucherOnNthPair`,
        
		COALESCE((SELECT COALESCE(RunningBalance,0) as Balance
        	FROM ewalletledger
            WHERE MemberID = mentry.`MemberID`
            ORDER BY LedgerID DESC,
            DateTimeEarned DESC
            LIMIT 1 
            )
        ,0) as MemberEWalletBalance,
        mentry.AccumulatedRewards,
        mentry.GeneratedPair
    FROM memberentry mentry
    INNER JOIN packagematchingcomm pckgmatchcom ON (pckgmatchcom.PackageID = mentry.`PackageID`);
        OPEN cursorEntryMatch;
    SELECT FOUND_ROWS() INTO cursorEntryMatchCount;    	
    if(cursorEntryMatchCount > 0) then      
        SET cursorEntryMatchCntr = 0;
        LoopMatchingEntries:WHILE cursorEntryMatchCntr < cursorEntryMatchCount DO        
    		
            SET intEntryID = 0;
            SET intMemberID = 0;
            SET dblLBPV = 0;
            SET dblRBPV = 0;
            SET intPackageID = 0;
            SET dblRequiredBPV = 0;
            SET dblPairingAmount = 0;
            SET intMaxMatchPerDay = 0;
            SET intVoucherOnNthPair = 0;
            SET dblEWalletRunningBal = 0;
            SET dblAccuRewards = 0;
            SET intGeneratedPair = 0;
            FETCH cursorEntryMatch
            INTO 
                intEntryID,
                intMemberID,
                dblLBPV,
                dblRBPV,
                intPackageID,
                dblRequiredBPV,
                dblPairingAmount,
                intMaxMatchPerDay,
                intVoucherOnNthPair,
                dblEWalletRunningBal,
                dblAccuRewards,
                intGeneratedPair;        
            
			SET dblAvailableMatch = FLOOR(if(dblLBPV <= dblRBPV, dblLBPV, dblRBPV) / if(dblRequiredBPV = 0, 1, dblRequiredBPV));
            
            if(dblAvailableMatch > 0) then
            	
            	if(dblAvailableMatch > intMaxMatchPerDay) then
                	SET dblAvailableMatch = intMaxMatchPerDay;
                end if;
                
                SET intMatchCntr = 0;
                LoopGenerateMatch:WHILE intMatchCntr < dblAvailableMatch DO        
					
                	SET intGeneratedPair = intGeneratedPair + 1;
                    SET isGenerateVoucher = 1;
            		SET isGenerateVoucher = intGeneratedPair % intVoucherOnNthPair;
			
            		if(isGenerateVoucher = 0) then
                    
						SET strVoucherNo = '';
                        SET isVoucherNotExist = 1;
                        LoopCheckVoucher:WHILE isVoucherNotExist > 0 DO        
                              
                            SET strVoucherNo = '';
                            SELECT CAST(FLOOR(rand()*1000000) as CHAR) as voucherno
                            INTO 
                                strVoucherNo;
                                  
                            SET isVoucherNotExist = 0;
                            SELECT COUNT(*)
                            FROM `membervoucher`
                            WHERE VoucherCode = strVoucherNo
                            INTO 
                                isVoucherNotExist;
                                  
                        END WHILE;                         
                        if(strVoucherNo != '') then
                          INSERT INTO membervoucher SET
                              MemberEntryID = intEntryID,
                              VoucherCode = strVoucherNo,
                              VoucherAmount = dblPairingAmount,
                              NthPair = intGeneratedPair,
                              Remarks = CONCAT('Voucher From Match No. ', CAST(intGeneratedPair as CHAR)),
                              `Status` = 'Available',
                              `CreatedByID` = 1,
                              `DateTimeCreated` = NOW(),
                              `UpdatedByID` = 1,
                              `DateTimeUpdated` = NOW();
                        end if;

                                                UPDATE `memberentry` SET
                            GeneratedPair = intGeneratedPair
                        WHERE EntryID = intEntryID;

                    else
                                                INSERT INTO ewalletledger SET
                            ComplanID = COMPLAN_ID,
                            MemberID = intMemberID,
                            EarnedFromMemberID = intMemberID,
                            LevelNo = 0,
                            DateTimeEarned = recCurrentDateTime,
                            EarnedMonth = MONTH(recCurrentDateTime),
                            EarnedYear = YEAR(recCurrentDateTime),
                            INAmount = dblPairingAmount,
                            OUTAmount = 0,
                            OldBalance = dblEWalletRunningBal,
                            RunningBalance = (dblEWalletRunningBal + dblPairingAmount),
                            Remarks = CONCAT('Commission from your Matching No. ', CAST(intGeneratedPair  as CHAR)),
                            `Status` = 'Approved',
                            TransactionRefID = 0,
                            DateTimeCreated = curDateTime,
                            DateTimeUpdated = curDateTime;

                        SET dblEWalletRunningBal = dblEWalletRunningBal + dblPairingAmount;

                                                UPDATE `memberentry` SET
                            AccumulatedRewards = AccumulatedRewards + dblPairingAmount,
                            GeneratedPair = intGeneratedPair
                        WHERE EntryID = intEntryID;

                    end if;

                    SET intMatchCntr = intMatchCntr + 1;
                        
                END WHILE; 
                if(dblAvailableMatch < intMaxMatchPerDay) then	
                    INSERT INTO memberentrymatch SET
                        HeadEntryID = intEntryID,
                        EntryID = intEntryID,
                        LevelNo = 0,
                        MatchPosition = '',
                        BPV = ((dblAvailableMatch * dblRequiredBPV) * (-1)),
                        LRunningBalance = dblLBPV - (dblAvailableMatch * dblRequiredBPV),
                        RRunningBalance = dblRBPV - (dblAvailableMatch * dblRequiredBPV),
                        Remarks = CONCAT('Matching Commission on ', CAST(recCurrentDateTime as CHAR));
                else
                    INSERT INTO memberentrymatch SET
                        HeadEntryID = intEntryID,
                        EntryID = intEntryID,
                        LevelNo = 0,
                        MatchPosition = '',
                        BPV = if(dblLBPV > dblRBPV, dblLBPV, dblRBPV),
                        LRunningBalance = 0,
                        RRunningBalance = 0,
                        Remarks = CONCAT('Matching Commission on ', CAST(recCurrentDateTime as CHAR));
                end if; 
                
            end if;
        	
            SET cursorEntryMatchCntr = cursorEntryMatchCntr + 1;
            
      	END WHILE;     
    end if;
    
 
 
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spGenerateSponsorCommission`(IN `recEntryID` BIGINT(20), IN `recCurrentDateTime` DATETIME)
BEGIN

 	
	DECLARE COMPLAN_ID BIGINT(13) DEFAULT 2;
	DECLARE curDateTime DATETIME DEFAULT NOW();
	DECLARE intMemberID BIGINT(13) DEFAULT 0;
	DECLARE strEntryCode VARCHAR(50) DEFAULT 0;
	DECLARE strPackage VARCHAR(150) DEFAULT 0;
	DECLARE intSponsorMemberID BIGINT(13) DEFAULT 0;
	DECLARE dblSponsorCommission DOUBLE(13,3) DEFAULT 0;
	DECLARE dblEWalletRunningBal DOUBLE(13,3) DEFAULT 0;
    DECLARE dblAccuRewards DOUBLE(13,3) DEFAULT 0;
    
    SET intMemberID = 0;
    SET strEntryCode = '';
    SET strPackage = '';
    SET intSponsorMemberID = 0;
    SET dblSponsorCommission = 0;
    SET dblEWalletRunningBal = 0;
    SET dblAccuRewards = 0;
  	SELECT 
    	mentry.`MemberID`,
        mentry.`EntryCode`,
        pckg.Package,
    	sentry.`MemberID` as SponsorID,
    	pckg.`SponsorCommission`,
        COALESCE((SELECT COALESCE(RunningBalance,0) as Balance
        	FROM ewalletledger
            WHERE MemberID = sentry.`MemberID`
            ORDER BY LedgerID DESC,
            DateTimeEarned DESC
            LIMIT 1 
            )
        ,0) as SponsorEWalletBalance,
		COALESCE(sentry.AccumulatedRewards,0) as AccumulatedRewards
  	FROM `memberentry` mentry
  	INNER JOIN `memberentry` sentry ON (sentry.EntryID = mentry.SponsorEntryID)
  	INNER JOIN `package` pckg ON (mentry.`PackageID` = pckg.`PackageID`)
  	WHERE mentry.`EntryID` = recEntryID
    INTO 
    	intMemberID,
        strEntryCode,
        strPackage,
        intSponsorMemberID,
        dblSponsorCommission,
        dblEWalletRunningBal,
        dblAccuRewards;
 	
    if(intSponsorMemberID > 0) then
    	INSERT INTO ewalletledger SET
    		ComplanID = COMPLAN_ID,
        	MemberID = intSponsorMemberID,
            EarnedFromMemberID = intMemberID,
            LevelNo = 1,
            DateTimeEarned = recCurrentDateTime,
            EarnedMonth = MONTH(recCurrentDateTime),
            EarnedYear = YEAR(recCurrentDateTime),
            INAmount = dblSponsorCommission,
            OUTAmount = 0,
            OldBalance = dblEWalletRunningBal,
            RunningBalance = (dblEWalletRunningBal + dblSponsorCommission),
            Remarks = CONCAT('Sponsor Commission from ', strPackage ,' Entry Code : ',strEntryCode),
            `Status` = 'Approved',
            TransactionRefID = recEntryID,
            DateTimeCreated = curDateTime,
            DateTimeUpdated = curDateTime;
                UPDATE `memberentry` SET
            AccumulatedRewards = (dblAccuRewards + dblSponsorCommission)
        WHERE MemberID = intSponsorMemberID;
            
    end if;


END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spSaveEWalletWithdrawal`(IN `recWithdrawalID` BIGINT(20), IN `recCurrentDateTime` DATETIME)
BEGIN
 
       
    DECLARE COMPLAN_ID BIGINT(13) DEFAULT 6;
    DECLARE curDateTime DATETIME DEFAULT NOW();
    DECLARE intWithdrawByMemberID BIGINT(13) DEFAULT 0;
    DECLARE strWithdrawalNo VARCHAR(50) DEFAULT 0;
    DECLARE dblApprovedAmount DOUBLE(13,3) DEFAULT 0;
    DECLARE dblOldEWalletBal DOUBLE(13,3) DEFAULT 0;
       
    SET intWithdrawByMemberID = 0;
    SET strWithdrawalNo = "";
    SET dblApprovedAmount = 0;
    SET dblOldEWalletBal = 0;
    SELECT 
   		ewdraw.WithdrawByMemberID,
   		ewdraw.`WithdrawalNo`,
   		COALESCE(ewdraw.`ApprovedAmount`,0) as ApprovedAmount,
   		COALESCE((SELECT COALESCE(RunningBalance,0) as Balance
				FROM ewalletledger
                WHERE MemberID = ewdraw.WithdrawByMemberID
                ORDER BY LedgerID DESC,
                DateTimeEarned DESC                
                LIMIT 1 
 			)
		,0) as MemberEWalletBalance
    FROM `ewalletwithdrawal` ewdraw
    WHERE ewdraw.`WithdrawalID` = recWithdrawalID
    INTO 
        intWithdrawByMemberID,
        strWithdrawalNo,
        dblApprovedAmount,
        dblOldEWalletBal;
 
    if(dblApprovedAmount > 0) then
        INSERT INTO ewalletledger SET
            ComplanID = COMPLAN_ID,
            MemberID = intWithdrawByMemberID,
            EarnedFromMemberID = intWithdrawByMemberID,
            LevelNo = 0,
            DateTimeEarned = recCurrentDateTime,
            EarnedMonth = MONTH(recCurrentDateTime),
            EarnedYear = YEAR(recCurrentDateTime),
            INAmount = 0,
            OUTAmount = dblApprovedAmount,
            OldBalance = dblOldEWalletBal,
            RunningBalance = (dblOldEWalletBal - dblApprovedAmount),
            Remarks = CONCAT('Withdrawal No. ',strWithdrawalNo),
            `Status` = 'Approved',
            TransactionRefID = recWithdrawalID,
            DateTimeCreated = curDateTime,
            DateTimeUpdated = curDateTime;
            
                UPDATE `memberentry` SET
            AccumulatedEncashed = AccumulatedEncashed + dblApprovedAmount
        WHERE MemberID = intWithdrawByMemberID;
        
    end if;


END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `spSetMemberEntryRank`(
        IN `recMemberEntryID` BIGINT,
        IN `recCurrentDateTime` DATETIME
    )
BEGIN
 	DECLARE dblTotalAPP DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblTotalAGP DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel1 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel1Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel1APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel1AGPRV DOUBLE(13,3) DEFAULT 0;
    
 	DECLARE strRankLevel2 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel2Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel2APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel2AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel3 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel3Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel3APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel3AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel4 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel4Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel4APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel4AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel5 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel5Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel5APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel5AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel6 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel6Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel6APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel6AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel7 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel7Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel7APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel7AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel8 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel8Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel8APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel8AGPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE strRankLevel9 VARCHAR(150) DEFAULT '';
 	DECLARE dblRankLevel9Percent DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel9APPRV DOUBLE(13,3) DEFAULT 0;
 	DECLARE dblRankLevel9AGPRV DOUBLE(13,3) DEFAULT 0;
    
    SET strRankLevel1 = '';
    SET dblRankLevel1Percent = 0;
    SET dblRankLevel1APPRV = 0;
    SET dblRankLevel1AGPRV = 0;
    SET strRankLevel2 = '';
    SET dblRankLevel2Percent = 0;
    SET dblRankLevel2APPRV = 0;
    SET dblRankLevel2AGPRV = 0;
    SET strRankLevel3 = '';
    SET dblRankLevel3Percent = 0;
    SET dblRankLevel3APPRV = 0;
    SET dblRankLevel3AGPRV = 0;
    SET strRankLevel4 = '';
    SET dblRankLevel4Percent = 0;
    SET dblRankLevel4APPRV = 0;
    SET dblRankLevel4AGPRV = 0;
    SET strRankLevel5 = '';
    SET dblRankLevel5Percent = 0;
    SET dblRankLevel5APPRV = 0;
    SET dblRankLevel5AGPRV = 0;
    SET strRankLevel6 = '';
    SET dblRankLevel6Percent = 0;
    SET dblRankLevel6APPRV = 0;
    SET dblRankLevel6AGPRV = 0;
    SET strRankLevel7 = '';
    SET dblRankLevel7Percent = 0;
    SET dblRankLevel7APPRV = 0;
    SET dblRankLevel7AGPRV = 0;
    SET strRankLevel8 = '';
    SET dblRankLevel8Percent = 0;
    SET dblRankLevel8APPRV = 0;
    SET dblRankLevel8AGPRV = 0;
    SET strRankLevel9 = '';
    SET dblRankLevel9Percent = 0;
    SET dblRankLevel9APPRV = 0;
    SET dblRankLevel9AGPRV = 0;
    SELECT 
 		pckrank.`RankLevel1`,
 		COALESCE(pckrank.`RankLevel1Percent`,0) as RankLevel1Percent,
        COALESCE(pckrank.`RankLevel1APPRV`,0) as RankLevel1APPRV,
        COALESCE(pckrank.`RankLevel1AGPRV`,0) as RankLevel1AGPRV,
 		pckrank.`RankLevel2`,
 		COALESCE(pckrank.`RankLevel2Percent`,0) as RankLevel2Percent,
        COALESCE(pckrank.`RankLevel2APPRV`,0) as RankLevel2APPRV,
        COALESCE(pckrank.`RankLevel2AGPRV`,0) as RankLevel2AGPRV,
 		pckrank.`RankLevel3`,
 		COALESCE(pckrank.`RankLevel3Percent`,0) as RankLevel3Percent,
        COALESCE(pckrank.`RankLevel3APPRV`,0) as RankLevel3APPRV,
        COALESCE(pckrank.`RankLevel3AGPRV`,0) as RankLevel3AGPRV,
 		pckrank.`RankLevel4`,
 		COALESCE(pckrank.`RankLevel4Percent`,0) as RankLevel4Percent,
        COALESCE(pckrank.`RankLevel4APPRV`,0) as RankLevel4APPRV,
        COALESCE(pckrank.`RankLevel4AGPRV`,0) as RankLevel4AGPRV,
 		pckrank.`RankLevel5`,
 		COALESCE(pckrank.`RankLevel5Percent`,0) as RankLevel5Percent,
        COALESCE(pckrank.`RankLevel5APPRV`,0) as RankLevel5APPRV,
        COALESCE(pckrank.`RankLevel5AGPRV`,0) as RankLevel5AGPRV,
 		pckrank.`RankLevel6`,
 		COALESCE(pckrank.`RankLevel6Percent`,0) as RankLevel6Percent,
        COALESCE(pckrank.`RankLevel6APPRV`,0) as RankLevel6APPRV,
        COALESCE(pckrank.`RankLevel6AGPRV`,0) as RankLevel6AGPRV,
 		pckrank.`RankLevel7`,
 		COALESCE(pckrank.`RankLevel7Percent`,0) as RankLevel7Percent,
        COALESCE(pckrank.`RankLevel7APPRV`,0) as RankLevel7APPRV,
        COALESCE(pckrank.`RankLevel7AGPRV`,0) as RankLevel7AGPRV,
 		pckrank.`RankLevel8`,
 		COALESCE(pckrank.`RankLevel8Percent`,0) as RankLevel8Percent,
        COALESCE(pckrank.`RankLevel8APPRV`,0) as RankLevel8APPRV,
        COALESCE(pckrank.`RankLevel8AGPRV`,0) as RankLevel8AGPRV,
 		pckrank.`RankLevel9`,
 		COALESCE(pckrank.`RankLevel9Percent`,0) as RankLevel9Percent,
        COALESCE(pckrank.`RankLevel9APPRV`,0) as RankLevel9APPRV,
        COALESCE(pckrank.`RankLevel9AGPRV`,0) as RankLevel9AGPRV
    FROM `memberentry` mentry 
    INNER JOIN `packagerank` as pckrank ON (mentry.`PackageID` = pckrank.`PackageID`)
    WHERE `mentry`.`EntryID` = recMemberEntryID
    LIMIT 1
    INTO 
    	strRankLevel1,
    	dblRankLevel1Percent,
      	dblRankLevel1APPRV,
    	dblRankLevel1AGPRV,
    	strRankLevel2,
    	dblRankLevel2Percent,
    	dblRankLevel2APPRV,
    	dblRankLevel2AGPRV,
    	strRankLevel3,
    	dblRankLevel3Percent,
    	dblRankLevel3APPRV,
    	dblRankLevel3AGPRV,
    	strRankLevel4,
    	dblRankLevel4Percent,
    	dblRankLevel4APPRV,
    	dblRankLevel4AGPRV,
    	strRankLevel5,
    	dblRankLevel5Percent,
    	dblRankLevel5APPRV,
    	dblRankLevel5AGPRV,
    	strRankLevel6,
    	dblRankLevel6Percent,
    	dblRankLevel6APPRV,
    	dblRankLevel6AGPRV,
    	strRankLevel7,
    	dblRankLevel7Percent,
    	dblRankLevel7APPRV,
    	dblRankLevel7AGPRV,
    	strRankLevel8,
    	dblRankLevel8Percent,
    	dblRankLevel8APPRV,
    	dblRankLevel8AGPRV,
    	strRankLevel9,
    	dblRankLevel9Percent,
    	dblRankLevel9APPRV,
    	dblRankLevel9AGPRV;
   
	
    SET dblTotalAPP = 0;
    SET dblTotalAGP = 0;
	SELECT
    	PersonalRunningBalance,
        GroupRunningBalance
    FROM memberentryorder
    WHERE HeadEntryID = recMemberEntryID
    ORDER BY AccumulatedOrderID DESC
    LIMIT 1
    INTO 
    	dblTotalAPP,
        dblTotalAGP;
    
    if(dblTotalAPP >= dblRankLevel9APPRV AND dblTotalAGP >= dblRankLevel9AGPRV) then
    	UPDATE memberentry SET 
            RankLevel = 9,
        	Rank = strRankLevel9
        WHERE EntryID = recMemberEntryID;
    elseif(dblTotalAPP >= dblRankLevel8APPRV AND dblTotalAGP >= dblRankLevel8AGPRV) then
    	UPDATE memberentry SET 
            RankLevel = 8,
        	Rank = strRankLevel8
        WHERE EntryID = recMemberEntryID;
    elseif(dblTotalAPP >= dblRankLevel7APPRV AND dblTotalAGP >= dblRankLevel7AGPRV) then
    	UPDATE memberentry SET 
            RankLevel = 7,
        	Rank = strRankLevel7
        WHERE EntryID = recMemberEntryID;
    elseif(dblTotalAPP >= dblRankLevel6APPRV AND dblTotalAGP >= dblRankLevel6AGPRV) then
    	UPDATE memberentry SET 
            RankLevel = 6,
        	Rank = strRankLevel6
        WHERE EntryID = recMemberEntryID;
    elseif(dblTotalAPP >= dblRankLevel5APPRV AND dblTotalAGP >= dblRankLevel5AGPRV) then
    	UPDATE memberentry SET 
            RankLevel = 5,
        	Rank = strRankLevel5
        WHERE EntryID = recMemberEntryID;
    elseif(dblTotalAPP >= dblRankLevel4APPRV AND dblTotalAGP >= dblRankLevel4AGPRV) then
    	UPDATE memberentry SET 
            RankLevel = 4,
        	Rank = strRankLevel4
        WHERE EntryID = recMemberEntryID;
    elseif(dblTotalAPP >= dblRankLevel3APPRV AND dblTotalAGP >= dblRankLevel3AGPRV) then
    	UPDATE memberentry SET 
            RankLevel = 3,
        	Rank = strRankLevel3
        WHERE EntryID = recMemberEntryID;
    elseif(dblTotalAPP >= dblRankLevel2APPRV AND dblTotalAGP >= dblRankLevel2AGPRV) then
    	UPDATE memberentry SET 
            RankLevel = 2,
        	Rank = strRankLevel2
        WHERE EntryID = recMemberEntryID;
    elseif(dblTotalAPP >= dblRankLevel1APPRV AND dblTotalAGP >= dblRankLevel1AGPRV) then
    	UPDATE memberentry SET 
            RankLevel = 1,
        	Rank = strRankLevel1
        WHERE EntryID = recMemberEntryID;
    end if;
END$$
DELIMITER ;
