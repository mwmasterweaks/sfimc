<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpGenerateRebates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
        DELIMITER $$
        CREATE PROCEDURE `spGenerateRebates`(IN `recCurrentDateTime` DATETIME)
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
            SET AcquiredByEntryID = MemberEntryID
            WHERE `TotalRebatableValue` >= `MaintainingBalance`
            AND MONTH(EndDate) = intCutOffMonth
            AND YEAR(EndDate) = intCutOffYear;

            UPDATE memberentrycutoff 
            SET AcquiredByEntryID = NULL
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
            
            SET intcoachmicjavCutOffID = 1;  
               
            GetUnAcquired:WHILE intcoachmicjavCutOffID > 0 DO
                SET intCutOffID = 0;
                SET intMemberEntryID = 0;
                SET intNewSponsorEntryID = 0;
                SET dblTotalPurchases = 0;
                SET dblTotalRebatableValue = 0;
                SET dblRebatesMaintainingBal = 0;
                " . // get last entry of the cutoff of the month
            "
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
                    " . //I think mao ni tong dynamic compresion
            //gina update niya ang AcquiredByEntryID sa memberentrycutoff. wala ko kabalo ngano
            "
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
                            
                            INSERT INTO tblTempCollection SET CutOffID = intCutOffID;

                            if(dblTotalRebatableValue >= dblRebatesMaintainingBal) then
                                UPDATE `memberentrycutoff` SET AcquiredByEntryID = intMemberEntryID
                                WHERE CutOffID IN (SELECT CutOffID FROM tblTempCollection);
                                DELETE FROM tblTempCollection;
                                LEAVE GetSponsor;
                            elseif(intAcquiredByEntryID > 0) then
                                UPDATE `memberentrycutoff` SET AcquiredByEntryID = intAcquiredByEntryID
                                WHERE CutOffID IN (SELECT CutOffID FROM tblTempCollection);
                                DELETE FROM tblTempCollection;
                                LEAVE GetSponsor;
                            elseif(intSponsorEntryID = intCompanyEntryID || intMemberEntryID = intSponsorEntryID) then
                                UPDATE `memberentrycutoff` SET AcquiredByEntryID = intCompanyEntryID
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
                                    UPDATE `memberentrycutoff` SET AcquiredByEntryID = intCompanyEntryID
                                    WHERE CutOffID IN (SELECT CutOffID FROM tblTempCollection);
                                    DELETE FROM tblTempCollection;
                                end if;
                            end if;

                            SET intNewSponsorEntryID = intSponsorEntryID;
                        END WHILE;           
                    end if;
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
            TotalAcquiredRebatableValue = 
                COALESCE((SELECT SUM(tblTempCutOff.TotalAcquiredRebatableValue)
                FROM tblTempCutOff
                WHERE tblTempCutOff.AcquiredByEntryID = memberentrycutoff.MemberEntryID),0)
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
                    cutoff.`CutOffID`, cutoff.`MemberEntryID`, mentry.MemberID, mentry.PackageID,
                    mentry.`SponsorEntryID`, sprentry.MemberID as SponsorMemberID, cutoff.`TotalPurchases`,
                    cutoff.`TotalRebatableValue`, cutoff.`TotalAcquiredRebatableValue`,
                    COALESCE((SELECT PersonalRunningBalance
                            FROM memberentryorder
                            WHERE HeadEntryID = cutoff.`MemberEntryID`
                            ORDER BY AccumulatedOrderID DESC
                            LIMIT 1),0) as PersonalRunningBalance,
                    COALESCE((SELECT GroupRunningBalance
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
                        LIMIT 1),0) as MemberEWalletBalance
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
        ";

        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = 'DROP PROCEDURE IF EXISTS your_procedure_name';

        DB::unprepared($sql);
    }
}
